<?

namespace App\Classes\Base;

class Words
{

    private array $words = [];

    private function setWords($words)
    {
        $this->words = $words;
    }

    public function countWords()
    {
        return count($this->words);
    }

    public function getAllWords()
    {
        return $this->words;
    }
    public function getMostPopular(int $quantity = 5)
    {

        $counts = array_count_values($this->getAllWords());
        arsort($counts, SORT_NUMERIC);

        return array_slice($counts, 0, $quantity);
    }
    public function addWords(array $words)
    {
        $this->setWords(array_merge($this->getAllWords(), $words));
    }

    public function addWordsFromString(string $str)
    {

        $str = strip_tags(mb_strtolower($str));
        preg_match_all("/\b(\w+)\b/ui", $str, $matches);

        $pretexts = array(
            'без',  'близ',  'в',     'во',     'вместо', 'вне',   'для',    'до',
            'за',   'и',     'из',    'изо',    'из',     'за',    'под',    'к',
            'ко',   'кроме', 'между', 'на',     'над',    'о',     'об',     'обо',
            'от',   'ото',   'перед', 'передо', 'пред',   'предо', 'по',     'под',
            'подо', 'при',   'про',   'ради',   'с',      'со',    'сквозь', 'среди',
            'у',    'через', 'но',    'или',    'по', 'его', 'как', 'не', 'что', 'а'
        );

        $words = array_diff($matches[1], $pretexts);
        $words = array_diff($words, array(''));
        foreach ($words as $key => $value) {
            if (strlen($value) < 2 || is_numeric($value)) {
                unset($words[$key]);
            }
        }
        $words = array_values($words);

        $this->setWords(array_merge($this->getAllWords(), $words));
    }
}
