<?php

namespace App\Utils\Enum;

use Illuminate\Support\Str;

trait EnumContract
{
    public static function values()
    {
        return array_column(self::cases(), 'value');
    }

    public static function keys()
    {
        return array_column(self::cases(), 'name');
    }

    public static function keyValue()
    {
        $data = [];
        foreach (self::cases() as $value) {
            $data[$value->name] = $value->value;
        }

        return $data;
    }

    public static function fromName(string $name): \UnitEnum
    {
        foreach (self::cases() as $item) {
            if ($name === $item->name) {
                return $item;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class);
    }

    public static function tryFromName(string $name): ?\UnitEnum
    {
        try {
            return self::fromName($name);
        } catch (\ValueError $error) {
            return null;
        }
    }

    public static function keyExists($key, \UnitEnum|array|null $cases): bool
    {
        if (is_null($cases)) {
            $cases = self::cases();
        }
        if ($cases instanceof \UnitEnum) {
            $cases = [$cases];
        }
        foreach ($cases as $case) {
            if ($key === $case || $key === $case->value) {
                return true;
            }
        }

        return false;
    }

    public static function translate(): array
    {
        $result = [];

        foreach (self::cases() as $item) {
            $translation = self::getTranslation($item);
            $formattedItem = self::formatEnumItem($item, $translation);
            $result[] = $formattedItem;
        }

        return $result;
    }

    private static function getTranslation(\UnitEnum $item): string
    {
        $enumName = strtolower($item->name);
        $fullClassName = explode('\\', get_class($item));
        $isModule = $fullClassName[0] === 'Modules';
        $className = Str::camel(end($fullClassName));

        $translationKeys = [
            'module' => $isModule ? [
                'classKey' => "{$fullClassName[1]}::enum.{$className}.{$enumName}",
                'key' => "{$fullClassName[1]}::enum.{$enumName}",
            ] : [],
            'default' => [
                'classKey' => "enum.{$className}.{$enumName}",
                'key' => "enum.{$enumName}",
            ]
        ];

        foreach ($translationKeys as $keys) {
            foreach ($keys as $translationKey) {
                $translation = __($translationKey);
                if ($translation !== $translationKey) {
                    return $translation;
                }
            }
        }

        return $enumName;
    }


    private static function formatEnumItem(\UnitEnum $item, string $translation): array
    {
        return [
            'key' => $item->name,
            'value' => $item->value,
            'translate' => $translation,
        ];
    }
}
