<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use App\Helpers\TimeHelper;
use App\Utils\JsonKit;

class CategoryController
{
    private CategoryRepository $categories;

    public function __construct(CategoryRepository $categories)
    {
        $this->categories = $categories;
    }

    public function getCategories(int $limit = 9999): array
    {
        $categories = $this->categories->getCategories($limit);

        foreach ($categories as &$category) {
            if (isset($category["latest_topic_date"])) {
                $category["latest_topic_date"] = TimeHelper::timeAgo(
                    $category["latest_topic_date"]
                );
            }
        }

        return $categories;
    }

    public function getCategoryInfoBySlug(string $slug): ?array
    {
        try {
            return $this->categories->getBySlug($slug);
        } catch (\Throwable $th) {
            JsonKit::fail("Bir Hata Oluştu: " . $th->getMessage());
            return null;
        }
    }

    public function getCategoryInfoByTopicId(int $topicId): ?array
    {
        try {
            return $this->categories->getByTopicId($topicId);
        } catch (\Throwable $th) {
            JsonKit::fail("Bir Hata Oluştu: " . $th->getMessage());
            return null;
        }
    }

    public function getTotalCategoryCount(): int
    {
        return $this->categories->getTotalCount();
    }
}
