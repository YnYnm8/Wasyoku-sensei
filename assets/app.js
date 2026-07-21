import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
*/
import './styles/app.css';
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// レシピ詳細ページ用：材料・調味料の人数調整（1レシピ分のみを想定した、最初のシンプルな版）
document.addEventListener('DOMContentLoaded', function () {
    const decreaseBtn = document.getElementById('decrease-btn');
    const increaseBtn = document.getElementById('increase-btn');
    const personCount = document.getElementById('person-count');
    const quantityElements = document.querySelectorAll('.quantity');

    // ボタンが、このページに、存在するときだけ、動くようにする
    if (!decreaseBtn || !increaseBtn) {
        return;
    }

    let count = 1;

    function updateQuantities() {
        quantityElements.forEach(function (el) {
            const base = parseFloat(el.closest('li').dataset.baseQuantity);
            el.textContent = (base * count).toFixed(1);
        });
        personCount.textContent = count;
    }

    increaseBtn.addEventListener('click', function () {
        count++;
        updateQuantities();
    });

    decreaseBtn.addEventListener('click', function () {
        if (count > 1) {
            count--;
            updateQuantities();
        }
    });
});

// 買い物リスト作成画面用：複数のレシピが同時に表示されるため、
// 上のシンプル版とは別に、レシピIDごとに独立して動く人数調整を用意する
// Turbo対応（DOMContentLoaded / turbo:load 両方に登録）は、以前学んだ通りのパターン
document.addEventListener('DOMContentLoaded', initShoppingListPersonCounters);
document.addEventListener('turbo:load', initShoppingListPersonCounters);

function initShoppingListPersonCounters() {
    // ページ上にある、id が "increase-btn-" で始まる要素（＝全レシピの「+」ボタン）を、まとめて探す
    const increaseButtons = document.querySelectorAll('[id^="increase-btn-"]');

    // 見つかった「+」ボタンを、1個ずつ処理する。
    // forEach の中で let count を宣言しているので、
    // レシピごとに、それぞれ独立した「人数カウンター」が作られる（お互いに干渉しない）
    increaseButtons.forEach(function (increaseBtn) {
        // ボタンの data-recipe-id 属性（Twig側で埋め込んだ、そのボタンが属するレシピのID）を取り出す
        const recipeId = increaseBtn.dataset.recipeId;

        // 同じレシピIDを持つ「−」ボタンと、人数表示欄を、IDから探す
        const decreaseBtn = document.getElementById('decrease-btn-' + recipeId);
        const personCountSpan = document.getElementById('person-count-' + recipeId);

        // 対応する要素が見つからなければ、何もしない（安全策）
        if (!decreaseBtn || !personCountSpan) {
            return;
        }

        // 画面に表示されている、現在の人数を初期値として読み取る
        // （Favorite.person の値が、Twig側で最初から表示されているので、そこから始める）
        let count = parseInt(personCountSpan.textContent, 10);

        function updateThisRecipe() {
            personCountSpan.textContent = count;

            // 「このレシピIDに属する」材料・調味料の <li> だけを探して、分量を再計算する
            // 他のレシピの <li>（別の data-recipe-id を持つもの）には影響しない
            document.querySelectorAll('li[data-recipe-id="' + recipeId + '"]').forEach(function (li) {
                const base = parseFloat(li.dataset.baseQuantity);
                const quantitySpan = li.querySelector('.quantity');
                if (quantitySpan) {
                    quantitySpan.textContent = (base * count).toFixed(1);
                }
            });
        }

        increaseBtn.addEventListener('click', function () {
            count++;
            updateThisRecipe();
        });

        decreaseBtn.addEventListener('click', function () {
            if (count > 1) {
                count--;
                updateThisRecipe();
            }
        });
    });
}