<?
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Base\Words,
    App\Classes\Base\Categories,
    App\Classes\Base\DateDiff,
    App\Classes\Sources\SourceLenta,
    App\Classes\Sources\SourceMK,
    App\Classes\Parser\ParserSimpleXML,
    App\Classes\Parser\ParserReaderXML;


$all_words = new Words();
$dates = new DateDiff();
$categories = new Categories();
$source = new SourceMK();

$parser = new ParserSimpleXML($source);

$post_collection = $parser->getData();
$data = $post_collection->getAllPosts();
$sorted_data = $post_collection->getSortedByCategory();

foreach ($data as $key => $post) {
    $all_words->addWords($post->getWords());
    $dates->addDate($post->getDatePublish());
    $categories->addCategory($post->getCategory());
}

?>
<link rel="stylesheet" type="text/css" href="/../../frontend/style.css">
<h1>Результаты парсера</h1>
<div class="main">
    <div class="section">
        <div class="group">
            <div class="simple-block">
                <div class="title">Количество постов:</div>
                <div class="description"><?= $post_collection->countPosts() ?></div>
            </div>
            <div class="simple-block">
                <div class="title">Cреднее количество слов в посте:</div>
                <div class="description"><?= round($all_words->countWords() / $post_collection->countPosts()) ?></div>
            </div>
            <div class="simple-block">
                <div class="title">Дата последнего поста [прошло дней с последнего поста]:</div>
                <div class="description">
                    <?
                    $res = current($data)->getDateDiffInfo();
                    echo $res['datePublish'] . " [" . $res['diff'] . "]";
                    ?>
                </div>
            </div>
            <div class="simple-block">
                <div class="title">Среднее время между постами:</div>
                <div class="description"><?= $dates->getCommonDiff() ?></div>
            </div>
        </div>
        <div class="group">
            <div class="sub-block">
                <div class="title">10 популярных слов во всех постах и их количество </div>
                <div class="description top-words">
                    <?
                    $most_popular_words = $all_words->getMostPopular(10);
                    foreach ($most_popular_words as $word => $count) {
                    ?>
                        <div class="item">
                            <?= $word . " - " . $count ?>
                        </div>
                    <?
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="group">
            <div class="sub-block">
                <div class="title">Список категорий</div>
                <div class="description">
                    <?
                    $list_categories = $categories->getAllCategories();
                    foreach ($list_categories as $key => $category) {
                    ?>
                        <div class="item"><?= $category ?></div>
                    <?
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="group">
            <div class="sub-block">
                <div class="title">Список категорий по популярности</div>
                <div class="description">
                    <?
                    $list_categories = $categories->getMostPopular();
                    foreach ($list_categories as $category => $quantity) {
                    ?>
                        <div class="item"><?= $category . " - " . $quantity ?></div>
                    <?
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="posts">
            <?
            foreach ($data as $key => $post) {
            ?>
                <div class="post">
                    <div class="image"><img src="<?= $post->getPreviewPhotoPath() ?>"></div>
                    <div class="detail">
                        <div class="common">
                            <div class=" item category">[<?= $post->getCategory(); ?>]</div>
                            <div class='item title'><?= $post->getTitle(); ?></div>
                            <div class="item time">опубликовано <?= $post->getDatePublish()->format('d.m.Y H:i:s'); ?></div>
                        </div>
                        <div class="description"><?= $post->getDescription(); ?></div>
                    </div>
                    <?/*<div class="item"><?=current($post->getPopularWords())?></div>*/ ?>
                </div>
            <?
            }
            ?>
        </div>
    </div>

</div>

<?
