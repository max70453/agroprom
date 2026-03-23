const products = [
    { 
        id: 1, 
        name: 'ПК 1-18 Куры несушки от 16 недель', 
        category: 'birds', 
        price: 900, 
        unit: 'мешок',
        description: 'Полнорационный комбикорм для кур-несушек' 
    },
    { id: 2, name: 'ПК 3-4 Молодняк кур несушек 9-17 недель', category: 'birds', price: 830, unit: 'мешок', description: 'Комбикорм для молодняка кур-несушек' },
    { id: 3, name: 'ПК 2-6 Молодняк кур-несушек 1-8 недель', category: 'birds', price: 1100, unit: 'мешок', description: 'Стартовый комбикорм для цыплят' },
    { id: 4, name: 'ПК 5-4 Бройлеры 1-3 недели (Старт)', category: 'birds', price: 1250, unit: 'мешок', description: 'Стартовый комбикорм для бройлеров' },
    { id: 5, name: 'ПК 6-6 Бройлеры 4-5 недель (Откорм)', category: 'birds', price: 1200, unit: 'мешок', description: 'Комбикорм для откорма бройлеров' },
    { id: 6, name: 'ПК 6-7 Бройлеры от 6 недель (Финиш)', category: 'birds', price: 1100, unit: 'мешок', description: 'Финишный комбикорм для бройлеров' },
    { id: 7, name: 'ПК-50 Поросята сосуны до 2-х месяцев', category: 'pigs', price: 1000, unit: 'мешок', description: 'Комбикорм для поросят-сосунов' },
    { id: 8, name: 'ПК-55 Мясной откорм свиней', category: 'pigs', price: 800, unit: 'мешок', description: 'Комбикорм для откорма свиней' },
    { id: 9, name: 'ПК 91 Для взрослых кроликов', category: 'rabbits', price: 760, unit: 'мешок', description: 'Полнорационный комбикорм для кроликов' },
    { id: 10, name: 'ПК 1-24 Перепела от 7 недель и старше', category: 'quail', price: 1050, unit: 'мешок', description: 'Комбикорм для перепелов' },
    { id: 11, name: 'ПК 11-1 Молодняк индейки 1-8 недель', category: 'turkey', price: 1400, unit: 'мешок', description: 'Стартовый комбикорм для индеек' },
    { id: 12, name: 'Комбикорм для КРС', category: 'cattle', price: 850, unit: 'мешок', description: 'Полнорационный комбикорм для КРС' },
];

const STORAGE_KEY = 'kaskad_saved_products';

function renderProducts(filter = 'all') {
    const grid = document.getElementById('productsGrid');
    if (!grid) return;
    
    const filtered = filter === 'all' ? products : products.filter(p => p.category === filter);
    
    grid.innerHTML = filtered.map(p => `
        <div class="product-card">
            <div class="product-image">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:80px;height:80px;color:#4CAF50">
                    <path d="M20 6H17.82C17.4 4.84 16.3 4 15 4c-1.66 0-3 1.34-3 3 0 .57.2 1.13.58 1.5L9.5 11 6 8c-.65-.65-1.5-1-2.5-1-1.65 0-3 1.35-3 3v1h13v2h2V6zm-4 8h-2v2h-2v-2h-2v-2h2v-2h2v2h2v2z"/>
                </svg>
            </div>
            <div class="product-info">
                <h3>${p.name}</h3>
                <p>${p.description}</p>
                <div class="product-meta">
                    <div class="product-price">${p.price} <span>₽/${p.unit}</span></div>
                    <button class="product-btn" onclick="saveProduct(${p.id})">В избранное</button>
                </div>
            </div>
        </div>
    `).join('');
}

function filterProducts(category) {
    renderProducts(category);
    document.querySelectorAll('.category-card').forEach(card => {
        card.classList.remove('active');
    });
    if (event && event.target.closest('.category-card')) {
        event.target.closest('.category-card').classList.add('active');
    }
}

function saveProduct(id) {
    const product = products.find(p => p.id === id);
    if (!product) return;
    
    let saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
    
    if (!saved.some(p => p.id === id)) {
        saved.unshift(product);
        if (saved.length > 10) saved = saved.slice(0, 10);
        localStorage.setItem(STORAGE_KEY, JSON.stringify(saved));
        alert('Товар добавлен в избранное!');
    } else {
        alert('Товар уже в избранном');
    }
}

function getSavedProducts() {
    return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
}

function openSavedProducts() {
    const modal = document.getElementById('savedModal');
    const list = document.getElementById('savedProductsList');
    if (!modal || !list) return;
    
    const saved = getSavedProducts();

    if (saved.length === 0) {
        list.innerHTML = '<p>У вас пока нет избранных товаров</p>';
    } else {
        list.innerHTML = saved.map(p => `
            <div class="saved-item">
                <h4>${p.name}</h4>
                <p>${p.price} ₽/${p.unit}</p>
            </div>
        `).join('');
    }
    modal.classList.add('active');
}

function closeModal() {
    const modal = document.getElementById('savedModal');
    if (modal) modal.classList.remove('active');
}

function openCalculator(e) {
    if (e) e.preventDefault();
    const modal = document.getElementById('calculatorModal');
    if (modal) modal.classList.add('active');
}

function closeCalculator() {
    const modal = document.getElementById('calculatorModal');
    if (modal) modal.classList.remove('active');
}

function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    if (modal) modal.classList.remove('active');
    closeCalculator();
}

function clearSavedProducts() {
    localStorage.removeItem(STORAGE_KEY);
    openSavedProducts();
}

function downloadPrice() {
    window.open('https://cloud.mail.ru/public/tDnQ/oCjrweZR4', '_blank');
}

function submitForm(e) {
    e.preventDefault();
    alert('Заявка отправлена! Мы свяжемся с вами в ближайшее время.');
}

function submitRecipeForm(e) {
    e.preventDefault();
    
    const formData = {
        animalType: document.getElementById('animalType').value,
        breed: document.getElementById('breed').value,
        productivity: document.getElementById('productivity').value,
        age: document.getElementById('age').value,
        volume: document.getElementById('volume').value,
        deliveryRegion: document.getElementById('deliveryRegion').value,
        specialRequirements: document.getElementById('specialRequirements').value,
        contactName: document.getElementById('contactName').value,
        contactPhone: document.getElementById('contactPhone').value,
        contactEmail: document.getElementById('contactEmail').value,
        submittedAt: new Date().toISOString()
    };
    
    localStorage.setItem('kaskad_recipe_request_' + Date.now(), JSON.stringify(formData));
    
    document.getElementById('calculatorModal').style.display = 'none';
    document.getElementById('successModal').style.display = 'flex';
    document.getElementById('recipeForm').reset();
}

document.addEventListener('DOMContentLoaded', function() {
    renderProducts();
    
    const savedModal = document.getElementById('savedModal');
    const calculatorModal = document.getElementById('calculatorModal');
    const successModal = document.getElementById('successModal');
    
    if (savedModal) {
        savedModal.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    }
    
    if (calculatorModal) {
        calculatorModal.addEventListener('click', function(e) {
            if (e.target === this) closeCalculator();
        });
    }
    
    if (successModal) {
        successModal.addEventListener('click', function(e) {
            if (e.target === this) closeSuccessModal();
        });
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeCalculator();
            closeSuccessModal();
        }
    });
});