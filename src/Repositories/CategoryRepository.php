<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\DB;

class CategoryRepository
{
    public function getCategories(int $limit = 9999): array
    {
        return DB::table("categories")
            ->limit($limit)
            ->get();
    }

    public function getBySlug(string $slug): ?array
    {
        return DB::table("categories")
            ->where("slug", $slug)
            ->first();
    }

    public function getByTopicId(int $topicId): ?array
    {
        return DB::table("categories")
            ->join("topics", "id", "=", "topics.category_id")
            ->where("topics.id", $topicId)
            ->select(["categories.*"])
            ->first();
    }

    public function getTotalCount(): int
    {
        return count(DB::table("categories")->get());
    }
}
