document.addEventListener('DOMContentLoaded', function () {
    var favoriteButtons = document.querySelectorAll('.favorite-toggle');

    favoriteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            toggleFavorite(button);
        });
    });
});

function toggleFavorite(button) {
    if (button.dataset.loading === '1') {
        return;
    }

    var productId = button.dataset.productId;
    var originalText = button.textContent.trim();
    var formData = new FormData();
    formData.append('product_id', productId);

    button.dataset.loading = '1';
    button.disabled = true;
    button.classList.add('is-loading');
    button.textContent = 'Saving...';

    fetch('toggle_favorite.php', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(function (response) {
            return response.json().then(function (data) {
                return {
                    ok: response.ok,
                    data: data
                };
            });
        })
        .then(function (result) {
            if (!result.ok || !result.data.success) {
                if (result.data.login_required) {
                    window.location.href = 'login.php';
                    return;
                }

                throw new Error(result.data.message || 'Unable to update favorites.');
            }

            if (!result.data.favorited && button.dataset.removeCard === '1') {
                button.textContent = 'Removed';
                showFavoriteFeedback(button, result.data.message, 'success');
                removeFavoriteCard(button);
            } else {
                updateFavoriteButton(button, result.data.favorited);
                showFavoriteFeedback(button, result.data.message, 'success');
                playFavoriteAnimation(button, result.data.favorited);
            }
        })
        .catch(function (error) {
            button.textContent = originalText;
            showFavoriteFeedback(button, error.message, 'error');
        })
        .finally(function () {
            button.disabled = false;
            button.dataset.loading = '0';
            button.classList.remove('is-loading');
        });
}

function updateFavoriteButton(button, favorited) {
    button.dataset.favorited = favorited ? '1' : '0';
    button.classList.toggle('is-favorited', favorited);
    button.textContent = favorited ? 'Saved to Favorites' : 'Add to Favorites';
}

function showFavoriteFeedback(button, message, type) {
    var actionArea = button.closest('.product-detail-actions, .product-actions');
    var feedback = actionArea ? actionArea.querySelector('.favorite-feedback') : null;

    if (!feedback) {
        return;
    }

    feedback.textContent = message;
    feedback.className = 'favorite-feedback ' + type + ' is-visible';

    window.clearTimeout(feedback.feedbackTimer);
    feedback.feedbackTimer = window.setTimeout(function () {
        feedback.textContent = '';
        feedback.className = 'favorite-feedback';
    }, 2500);
}

function playFavoriteAnimation(button, favorited) {
    button.classList.remove('favorite-pop', 'favorite-unsave');

    window.requestAnimationFrame(function () {
        button.classList.add(favorited ? 'favorite-pop' : 'favorite-unsave');
    });

    window.setTimeout(function () {
        button.classList.remove('favorite-pop', 'favorite-unsave');
    }, 420);
}

function removeFavoriteCard(button) {
    var card = button.closest('.product-card');
    var grid = card ? card.closest('.product-grid') : null;
    var emptyState = document.getElementById('favorites-empty-state');

    if (!card || !grid) {
        return;
    }

    card.classList.add('is-removing');

    window.setTimeout(function () {
        card.remove();

        if (grid.querySelectorAll('.product-card').length === 0 && emptyState) {
            emptyState.hidden = false;
        }
    }, 220);
}
