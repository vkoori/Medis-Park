<?php

namespace App\Utils\Dto;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionProperty;
use ReflectionUnionType;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class BaseDto
{
    /** @var array<int, ReflectionProperty> $publicProperties */
    private array $publicProperties;

    final public function __construct()
    {
        $reflectionClass = new ReflectionClass(static::class);

        $publicProperties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($publicProperties as $property) {
            $this->publicProperties[$property->getName()] = $property;
        }
    }

    public static function fromArray(array $data): static
    {
        $instance = new static();

        foreach ($data as $key => $value) {
            $targetPropertyName = null;

            if (isset($instance->publicProperties[$key])) {
                $targetPropertyName = $key;
            } else {
                $camelKey = Str::camel($key);
                if (isset($instance->publicProperties[$camelKey])) {
                    $targetPropertyName = $camelKey;
                }
            }

            if ($targetPropertyName && isset($instance->publicProperties[$targetPropertyName])) {
                $instance->{$targetPropertyName} = self::castValue(
                    property: $instance->publicProperties[$targetPropertyName],
                    value: $value
                );
            }
        }

        return $instance;
    }

    public static function fromFormRequest(FormRequest $request)
    {
        return self::fromArray($request->validated());
    }

    public function hasField(string $propertyName): bool
    {
        return isset($this->publicProperties[$propertyName])
            && $this->publicProperties[$propertyName]->isInitialized($this);
    }

    public function getProvidedData(): array
    {
        $data = [];
        foreach ($this->publicProperties as $property) {
            if ($property->isInitialized($this)) {
                $propertyName = $property->getName();
                $data[$propertyName] = $this->convertValueToArrayRecursive($this->{$propertyName}, false);
            }
        }
        return $data;
    }

    public function getProvidedDataSnakeCase(): array
    {
        $data = [];
        foreach ($this->publicProperties as $property) {
            if ($property->isInitialized($this)) {
                $propertyName = $property->getName();
                $snakeCaseName = Str::snake($propertyName);
                $data[$snakeCaseName] = $this->convertValueToArrayRecursive($this->{$propertyName}, true);
            }
        }
        return $data;
    }

    private static function castValue(\ReflectionProperty $property, $value): mixed
    {
        $type = $property->getType();
        $isNullable = true;
        $types = ['mixed'];

        if ($type instanceof \ReflectionUnionType) {
            $types = array_map(fn($t) => $t->getName(), $type->getTypes());
            $isNullable = in_array('null', $types);
        } elseif ($type) {
            $isNullable = $type->allowsNull();
            $types = [$type->getName()];
        }

        $annotationType = self::getAnnotationType($property);
        if ($annotationType && str_ends_with($annotationType, '[]')) {
            $elementType = substr($annotationType, 0, -2);
            if (class_exists($elementType) && is_array($value)) {
                if (enum_exists($elementType)) {
                    $value = array_map(fn($item) => ($elementType)::from($item), $value);
                } else {
                    $value = array_map(fn($item) => $elementType::fromArray($item), $value);
                }
            }
        } else {
            $types = array_map(function ($typeName) {
                if (enum_exists($typeName)) {
                    return 'enum';
                }
                if (is_a($typeName, BaseDto::class, true)) {
                    return 'dto';
                }
                if (is_a($typeName, Carbon::class, true)) {
                    return 'carbon';
                }
                if (is_a($typeName, UploadedFile::class, true)) {
                    return 'uploadedFile';
                }
                return $typeName;
            }, $types);
        }

        $value = match (true) {
            $isNullable && $value === null => null,
            in_array('mixed', $types) => $value,
            in_array('int', $types) && filter_var($value, FILTER_VALIDATE_INT) => (int) $value,
            in_array('string', $types) && (
                is_string($value) ||
                is_numeric($value) ||
                (is_object($value) && $value instanceof \Stringable)
            ) => (string) $value,
            in_array('float', $types) && filter_var($value, FILTER_VALIDATE_FLOAT) => (float) $value,
            in_array('double', $types) && is_double($value) => (float) $value,
            in_array('bool', $types) => (bool) $value,
            in_array('array', $types) && is_array($value) => $value,
            in_array('iterable', $types) && (is_array($value) || $value instanceof \Traversable) => $value,
            in_array('callable', $types) && is_callable($value) => $value,
            in_array('enum', $types) && (ltrim((string)$type, '?'))::tryFrom($value) !== null => (ltrim((string)$type, '?'))::from($value),
            in_array('dto', $types) && is_array($value) => (ltrim((string)$type, '?'))::fromArray($value),
            in_array('carbon', $types) && is_array($value) => (ltrim((string)$type, '?'))::parse($value),
            in_array('carbon', $types) && $value instanceof Carbon => $value,
            in_array('uploadedFile', $types) && $value instanceof uploadedFile => $value,
            default => throw new \InvalidArgumentException("Invalid type for property {$property->getName()}"),
        };

        return $value;
    }

    private static function getAnnotationType(\ReflectionProperty $property): ?string
    {
        $docComment = $property->getDocComment();
        if ($docComment && preg_match('/@var\s+([^\s]+)/', $docComment, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function convertValueToArrayRecursive(mixed $value, bool $snakeCase): mixed
    {
        if (is_iterable($value)) {
            $convertedArray = [];
            foreach ($value as $key => $item) {
                $convertedArray[$key] = $this->convertValueToArrayRecursive($item, $snakeCase);
            }
            return $convertedArray;
        }

        if ($value instanceof BaseDto) {
            return $snakeCase ? $value->getProvidedDataSnakeCase() : $value->getProvidedData();
        }

        return $value;
    }

    public function __serialize(): array
    {
        return $this->getProvidedData();
    }

    public function __unserialize(array $data): void
    {
        $this->__construct();
        foreach ($data as $key => $value) {
            if (isset($this->publicProperties[$key])) {
                $this->{$key} = $value;
            }
        }
    }
}
