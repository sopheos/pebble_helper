<?php

namespace Pebble\Helpers;

class ArrayHelper
{
    /**
     * Convertit récursivement un objet en tableau
     *
     * @param object $object
     * @return array
     */
    public static function toArray($object): array
    {
        return json_decode(json_encode($object), true);
    }

    /**
     * Supprime les préfixes des clés d'un tableau
     * Utilisation sur un tableau de tableau :
     *
     * @param array $input
     * @param string $prefix
     * @return array
     */
    public static function removePrefix(array $input, string $prefix): array
    {
        if (!$input || !$prefix) return [];

        $output = [];
        $len = mb_strlen($prefix);

        foreach ($input as $key => $value) {
            if (mb_strpos($key, $prefix) === 0) {
                $key = mb_substr($key, $len);
            }

            $output[$key] = $value;
        }

        return $output;
    }

    /**
     * Ajoute un préfixe aux clés d'un tableau
     *
     * @param array $input
     * @param string $prefix
     * @return array
     */
    public static function addPrefix(array $input, string $prefix): array
    {
        foreach ($input as $key => $value) {
            $input[$prefix . $key] = $value;
            unset($input[$key]);
        }

        return $input;
    }

    /**
     * Renomme les clés d'un tableau
     *
     * @param array $input
     * @param array $replace : ['nom' => 'name']
     * @return array
     */
    public static function renameKey(array $input, array $replace): array
    {
        $output = [];

        foreach ($input as $key => $value) {
            if (isset($replace[$key])) {
                $key = $replace[$key];
            }
            $output[$key] = $value;
        }

        return $output;
    }

    /**
     * Execute une fonction de rappel sur chaque élément d'un tableau.
     * Conserve les clés et le tableau d'origine contrairement a array_walk
     *
     * Exemple :
     * $out = ArrayHelper::walk($input, [ArrayHelper::class, 'renameKey'], [
     *   'nom' => 'name'
     * ]);
     *
     * @param array $input
     * @param callable $callable
     * @param mixed ...$params
     * @return array
     */
    public static function walk(array $input, callable $callable, ...$params): array
    {
        $output = [];

        foreach ($input as $key => $value) {
            $output[$key] = call_user_func($callable, $value, ...$params);
        }

        return $output;
    }

    /**
     * @param array $input
     * @param string $key
     * @return array
     */
    public static function values(array $input, string $key): array
    {
        $values = [];
        foreach ($input as $item) {
            if (isset($item[$key])) $values[] = $item[$key];
        }

        return array_unique($values);
    }

    /**
     * Renvoi uniquement les clés $keys du tableau $input
     *
     * @param array $input
     * @param array $keys
     */
    public static function select(array $input, array $keys)
    {
        $output = [];
        foreach ($keys as $key) {
            $output[$key] = $input[$key] ?? null;
        }
        return $output;
    }

    /**
     * Renvoi si un tableau est multidimensionnel
     *
     * @param array $data
     * @return boolean
     */
    public static function isMulti(array $data): bool
    {
        foreach ($data as $value) {
            if (is_array($value)) {
                return true;
            }
        }
        return false;
    }
}
