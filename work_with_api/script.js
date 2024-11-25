const apiUrl = 'work_with_api/api.php';
const cityInput = document.getElementById('cityInput');
const citySuggestions = document.getElementById('citySuggestions');

// Обробник для введення тексту в поле input
cityInput.addEventListener('input', async () => {
    const query = cityInput.value.trim();
    if (query.length < 2) {
        citySuggestions.style.display = 'none'; // Ховаємо список, якщо текст дуже короткий
        return;
    }

    try {
        const response = await fetch(`${apiUrl}?action=getCities&query=${encodeURIComponent(query)}`);
        const cities = await response.json();

        if (cities && cities.data) {
            // Очищаємо попередні результати
            citySuggestions.innerHTML = '';

            // Заповнюємо список варіантами міст
            cities.data.forEach(city => {
                const li = document.createElement('li');
                li.textContent = city.Description;
                li.dataset.ref = city.Ref; // Зберігаємо Ref для кожного міста
                li.addEventListener('click', () => {
                    cityInput.value = city.Description; // Вставляємо вибране місто в input
                    citySuggestions.style.display = 'none'; // Сховати список після вибору
                    // Можна зберігати Ref вибраного міста для подальшої обробки
                    selectedCityRef = city.Ref;
                });
                citySuggestions.appendChild(li);
            });

            // Показуємо список
            citySuggestions.style.display = 'block';
        } else {
            citySuggestions.style.display = 'none';
        }
    } catch (error) {
        console.error('Помилка завантаження міст:', error);
        citySuggestions.style.display = 'none';
    }
});

// Сховуємо список, якщо користувач клікає поза полем введення або списком
document.addEventListener('click', (e) => {
    if (!cityInput.contains(e.target) && !citySuggestions.contains(e.target)) {
        citySuggestions.style.display = 'none';
    }
});



async function loadBranches(cityRef, deliveryType) {
    try {
        console.log(cityRef);
        const response = await fetch(`${apiUrl}?action=getBranches&cityRef=${cityRef}&deliveryType=${deliveryType}`);
        const data = await response.json();
        
        // Перевірка структури відповіді
        console.log(data); // Перевірка того, що отримуємо в відповіді
        
        const branches = data && Array.isArray(data.data) ? data.data : []; // Перевіряємо, чи є дані в масиві

        const branchSelect = document.getElementById('branch');

        const filteredBranches = branches.filter(branch => {
            if (deliveryType === 'Поштомат' && branch.Description.includes('Поштомат')) {
                return true;
            } else if (deliveryType === 'Відділення' && branch.Description.includes('Відділення')) {
                return true;
            } else if (deliveryType === 'Пункт' && branch.Description.includes('Пункт')) {
                return true;
            }
            return false;
        });

        
        if (filteredBranches.length > 0) {
            branchSelect.innerHTML = filteredBranches.map(branch => 
                `<option value="${branch.Ref}">${branch.Description}</option>`
            ).join('');
        } else {
            branchSelect.innerHTML = `<option value="">Відділення не знайдені</option>`;
        }
    } catch (error) {
        console.error('Помилка завантаження відділень:', error);
    }
}


// Обробка подій зміни міста або типу доставки
document.getElementById('orderForm').addEventListener('change', async () => {
    const city = cityInput.value;
    const deliveryType = document.querySelector('input[name="deliveryType"]:checked')?.value;
    const weight = parseFloat(document.getElementById('weight').value);

    // Перевірка на вагу для поштомату
    if (deliveryType === 'Поштомат' && weight > 30) {
        alert('Поштомат не підтримує доставку більше 30 кг');
        return;
    }

    // Завантаження відділень
    if (city && deliveryType) {
        await loadBranches(city, deliveryType);
    }
});


// Обробка форми для збереження замовлення
document.getElementById('orderForm').addEventListener('submit', async (e) => {
    e.preventDefault(); // Зупинити стандартну дію форми (перезавантаження сторінки)

    const data = {
        orderNumber: document.getElementById('orderNumber').value.trim(),
        weight: document.getElementById('weight').value.trim(),
        city: document.getElementById('cityInput').value.trim(),
        deliveryType: document.querySelector('input[name="deliveryType"]:checked')?.value.trim(),
        branch: document.getElementById('branch').value.trim(),
    };

    // Перевірка на клієнтській стороні
    if (Object.values(data).some(value => !value)) {
        alert('Будь ласка, заповніть усі поля!');
        return;
    }

    try {
        // Відправлення даних на сервер
        const response = await fetch(`${apiUrl}?action=saveOrder`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });

        const result = await response.json();
        if (result.error) {
            alert(`Помилка: ${result.error}`);
        } else {
            alert(result.message || 'Замовлення успішно оформлено!');
        }
    } catch (error) {
        console.error('Помилка відправки форми:', error);
        alert('Сталася помилка. Спробуйте знову.');
    }
});
