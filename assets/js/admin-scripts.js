document.addEventListener('DOMContentLoaded', () => {
    // Обработка действий над кастомными записями
    if (document.querySelector('#custom-posts-submit')) {
        document.querySelector('#custom-posts-submit').addEventListener('click', () => {
            const action = document.querySelector('#custom-post-action').value;
            const formData = new FormData();
            formData.append('action', 'handle_posts_action');
            formData.append('action_type', action);
            formData.append('_ajax_nonce', pluginData.nonce);

            fetch(pluginData.ajax_url, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
            })
                .then((response) => response.json())
                .then((result) => {
                    const responseDiv = document.querySelector('#custom-posts-response');
                    if (result.success) {
                        responseDiv.innerHTML = `<p>${result.data.message}</p>`;
                    } else {
                        responseDiv.innerHTML = `<p>Ошибка: ${result.data.message}</p>`;
                    }
                })
                .catch((error) => {
                    console.error('Ошибка AJAX:', error);
                });
        });
    }

    // Обработка сохранения настроек
    if (document.querySelector('#save-settings')) {
        document.querySelector('#save-settings').addEventListener('click', () => {
            const customOption = document.querySelector('#custom-option').value;
            fetch(pluginData.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'save_plugin_settings',
                    custom_option: customOption,
                    _ajax_nonce: pluginData.nonce,
                }),
                credentials: 'same-origin',
            })
                .then((response) => response.json())
                .then((result) => {
                    const responseDiv = document.querySelector('#settings-response');
                    if (result.success) {
                        responseDiv.innerHTML = `<p>${result.data.message}</p>`;
                    } else {
                        responseDiv.innerHTML = `<p>Ошибка: ${result.data.message}</p>`;
                    }
                })
                .catch((error) => {
                    console.error('Ошибка AJAX:', error);
                });
        });
    }
});