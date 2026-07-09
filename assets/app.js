
import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

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