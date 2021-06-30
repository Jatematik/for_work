        window.mySuccessForm = function ($form) {
           // Тут код
        }
        $('#form').each(function () { // айди формы
            $(this).data('success-callback', 'window.mySuccessForm');
        });
