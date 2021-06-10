<?

namespace App\Classes\Parser;

use App\Intefaces\IPostCollection;
use App\Intefaces\IParserSource;

abstract class Parser
{

    protected $source;

    protected IPostCollection $postCollection;
    abstract public function getRawData();

    public function __construct(IParserSource $source)
    {
        $this->source = $source;
    }
    public function getSourcePath()
    {
        return $this->source->getPath();
    }
}
