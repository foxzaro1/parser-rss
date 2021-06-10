<?

namespace App\Classes\Base;

class Categories
{
    private $categories = [];

    public function addCategory($category)
    {
        $this->categories[] = $category;
    }

    public function countCategories($unique = true)
    {
        return count(array_unique($this->categories));
    }

    public function getAllCategories($unique = true)
    {
        if ($unique) {
            return array_unique($this->categories);
        } else {
            return $this->categories;
        }
    }
    public function getMostPopular($quantity = false)
    {

        $counts = array_count_values($this->getAllCategories(false));
        arsort($counts, SORT_NUMERIC);
        if (is_numeric($quantity)) {
            return array_slice($counts, 0, $quantity);
        } else {
            return $counts;
        }
    }
}
