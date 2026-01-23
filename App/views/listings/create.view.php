<?php
loadPartials('head');
loadPartials('navbar');
loadPartials('top-banner');
?>

<!-- Форма публикации вакансии -->
<section class="flex justify-center items-center mt-20">
    <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-600 mx-6">
        <h2 class="text-4xl text-center font-bold mb-4">Создать объявление о работе</h2>
        <!-- <div class="message bg-red-100 p-3 my-3">Это сообщение об ошибке.</div>
        <div class="message bg-green-100 p-3 my-3">
          Это сообщение об успехе.
        </div> -->
        <form method="POST">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
                Информация о вакансии
            </h2>
            <div class="mb-4">
                <input
                        type="text"
                        name="title"
                        placeholder="Название должности"
                        class="w-full px-4 py-2 border rounded focus:outline-none"
                />
            </div>
            <div class="mb-4">
            <textarea
                    name="description"
                    placeholder="Описание вакансии"
                    class="w-full px-4 py-2 border rounded focus:outline-none"
            ></textarea>
            </div>
            <div class="mb-4">
                <input
                        type="text"
                        name="salary"
                        placeholder="Годовая зарплата"
                        class="w-full px-4 py-2 border rounded focus:outline-none"
                />
            </div>
            <div class="mb-4">
                <input
                        type="text"
                        name="requirements"
                        placeholder="Требования"
                        class="w-full px-4 py-2 border rounded focus:outline-none"
                />
            </div>
            <div class="mb-4">
                <input
                        type="text"
                        name="benefits"
                        placeholder="Преимущества"
                        class="w-full px-4 py-2 border rounded focus:outline-none"
                />
            </div>
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
                Информация о компании и местоположение
            </h2>
            <div class="mb-4">
                <input
                        type="text"
                        name="company"
                        placeholder="Название компании"
                        class="w-full px-4 py-2 border rounded focus:outline-none"
                />
            </div>
            <div class="mb-4">
                <input
                        type="text"
                        name="address"
                        placeholder="Адрес"
                        class="w-full px-4 py-2 border rounded focus:outline-none"
                />
            </div>
            <div class="mb-4">
                <input
                        type="text"
                        name="city"
                        placeholder="Город"
                        class="w-full px-4 py-2 border rounded focus:outline-none"
                />
            </div>
            <div class="mb-4">
                <input
                        type="text"
                        name="state"
                        placeholder="Штат/Область"
                        class="w-full px-4 py-2 border rounded focus:outline-none"
                />
            </div>
            <div class="mb-4">
                <input
                        type="text"
                        name="phone"
                        placeholder="Телефон"
                        class="w-full px-4 py-2 border rounded focus:outline-none"
                />
            </div>
            <div class="mb-4">
                <input
                        type="email"
                        name="email"
                        placeholder="Email для откликов"
                        class="w-full px-4 py-2 border rounded focus:outline-none"
                />
            </div>
            <button
                    class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none"
            >
                Сохранить
            </button>
            <a
                    href="/"
                    class="block text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded focus:outline-none"
            >
                Отмена
            </a>
        </form>
    </div>
</section>

<?php
loadPartials('bottom-banner');
loadPartials('footer');
?>
