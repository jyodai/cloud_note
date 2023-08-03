<?php

namespace App\Models\Traits;

use App\Consts\Common as C_Common;

trait Nested
{
    /**
     * @brief 親Idを渡すことで、自身の所属する階層を取得
     */
    public static function belongHierarchy(int $parentId): int
    {
        if ($parentId === 0) {
            return 1;
        }
        $entity = self::find($parentId);
        return $entity->hierarchy + 1;
    }

    /**
     * @brief 親Idを渡すことで、自身の所属する階層の次の表示順序を取得
     */
    public static function nextDisplayNum(int $parentId, string $column): int
    {
        $entities = self::where($column, $parentId)->get();
        return (count($entities) * C_Common::INCREMENT) + C_Common::INCREMENT;
    }

    public static function adjustOrder(int $parentId, string $column, array $where = []): void
    {
        $array = self::where($column, $parentId)
        ->where($where)
        ->orderBy('display_num', 'asc')
        ->get();
        for ($i = 0; $i < count($array); $i++) {
            $array[$i]['display_num'] = C_Common::INCREMENT * ($i + 1);
            $array[$i]->save();
        }
    }
}
