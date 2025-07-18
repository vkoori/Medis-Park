<?php

namespace App\Utils\Exceptions;

class ExceptionUtil
{
    public static function convertExceptionToArray(\Throwable $e)
    {
        return [
            'message' => $e->getMessage(),
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'trace' => self::sanitizeTrace($e->getTrace()),
            'previous' => $e->getPrevious() ? self::convertExceptionToArray($e->getPrevious()) : null,
        ];
    }

    protected static function sanitizeTrace(array $trace)
    {
        return array_map(function ($traceEntry) {
            if (isset($traceEntry['args'])) {
                $traceEntry['args'] = array_map([self::class, 'sanitizeArgument'], $traceEntry['args']);
            }
            return $traceEntry;
        }, $trace);
    }

    protected static function sanitizeArgument($arg)
    {
        if (is_iterable($arg)) {
            return self::sanitizeIterable($arg);
        } else {
            return self::sanitizeSingle($arg);
        }
    }

    private static function sanitizeIterable($arg)
    {
        $samples = [];
        $count = 0;

        foreach ($arg as $item) {
            if ($count < 3) {
                $samples[] = self::sanitizeSingle($item);
            }
            $count++;
        }

        if ($count > 3) {
            $samples[] = '...';
        }

        return sprintf('[%s of %d elements]: %s', ucwords(gettype($arg)), $count, json_encode($samples));
    }

    private static function sanitizeSingle($arg)
    {
        if (is_object($arg)) {
            return get_class($arg);
        } elseif (is_resource($arg)) {
            return sprintf('[Resource: %s]', get_resource_type($arg));
        } elseif (is_callable($arg)) {
            return '[Closure]';
        } else {
            return json_encode($arg);
        }
    }
}
