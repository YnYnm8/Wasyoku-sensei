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
    /**
     * Registers this repository for the Recipe entity.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * Finds recipes that contain every one of the given ingredient names (AND search).
     *
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

    /**
     * Returns recipes ordered by favorite count, most-favorited first. Used
     * for the "popular recipes" section on the home page.
     *
     * お気に入りの登録数が多い順に、レシピを取得する
     * ホームページの「人気のレシピ」に使用する
     *
     * @param int $limit 取得する件数（デフォルトは3件）
     * @return Recipe[] 人気順に並んだ、レシピの配列
     */
    public function findMostPopular(int $limit = 3): array
    {
        return $this->createQueryBuilder('r')
            // r（Recipe）から favorites（Favoriteとの関連）へ、橋渡しする
            // leftJoin を使うのは、まだ1件もお気に入りされていないレシピも、
            // 結果から消えずに表示されるようにするため
            ->leftJoin('r.favorites', 'f')

            // レシピのID（r.id）ごとに、まとめて集計できるようにする
            // これが無いと、「レシピごとの、お気に入り件数」を数えられない
            ->groupBy('r.id')

            // COUNT(f.id) で、各レシピの「お気に入りの件数」を数え、
            // DESC（多い順）で並び替える
            ->orderBy('COUNT(f.id)', 'DESC')

            // 上位、何件だけ取得するかを指定する（今回は3件）
            ->setMaxResults($limit)

            // ここまで組み立てた検索文を、実際に実行する
            ->getQuery()

            // 実行した結果を、配列として受け取る
            ->getResult()
        ;
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
