<?php
namespace App\Repository;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * 複数の材料名を受け取り、そのすべてを含むレシピだけを検索する（AND検索）
     *
     * @param array $ingredientNames 検索したい材料名の配列（例: ['poulet', 'oignon']）
     * @return Recipe[] 条件に一致したレシピの配列
     */
    public function findByIngredientNames(array $ingredientNames): array
    {
        // Recipeを検索するための、検索文の組み立てを開始する。
        // 今後 r という短い名前で Recipe を指す。
        $qb = $this->createQueryBuilder('r');

        // 材料の数だけ繰り返し、条件を1つずつ追加していく
        foreach ($ingredientNames as $index => $ingredientName) {

            // join（橋渡し）ごとに、名前が被らないよう専用のあだ名を作る
            // 例：1周目は ri0 / i0、2周目は ri1 / i1 になる
            $joinAlias = 'ri' . $index;
            $ingredientAlias = 'i' . $index;

            $qb
                // r（Recipe）から recipeIngredients（中間テーブル）へ橋渡しする
                ->innerJoin('r.recipeIngredients', $joinAlias)
                // 中間テーブルから、さらに ingredient（材料）へ橋渡しする
                ->innerJoin($joinAlias . '.ingredient', $ingredientAlias)
                // 材料名が、指定した文字を含んでいるものだけに絞り込む（部分一致）
                ->andWhere($ingredientAlias . '.name LIKE :name' . $index)
                // 空欄になっている :name部分に、実際に検索したい材料名を安全に当てはめる
                ->setParameter('name' . $index, '%' . $ingredientName . '%')
            ;
        }

        // 組み立てた検索文を実行し、結果を取得する
        return $qb->getQuery()->getResult();
    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}