// Complete Dummy Data for Vamika Enterprise

const dummyData = {
    // Areas
    areas: [
        { id: 1, name: "Gandhi Nagar", pincode: "110051", shops: 15, deliveryCharge: 50 },
        { id: 2, name: "Shahdara", pincode: "110032", shops: 12, deliveryCharge: 40 },
        { id: 3, name: "Preet Vihar", pincode: "110092", shops: 8, deliveryCharge: 30 },
        { id: 4, name: "Laxmi Nagar", pincode: "110092", shops: 10, deliveryCharge: 35 },
        { id: 5, name: "Geeta Colony", pincode: "110031", shops: 6, deliveryCharge: 45 }
    ],
    
    // Shops
    shops: [
        { 
            id: 1, 
            name: "Mohan Kirana Store", 
            area: "Gandhi Nagar", 
            owner: "Mohan Singh", 
            phone: "9876543210", 
            address: "Shop No. 12, Gandhi Nagar Market",
            lastOrder: "2 hours ago",
            totalOrders: 45,
            status: "active"
        },
        { 
            id: 2, 
            name: "Gupta General Store", 
            area: "Gandhi Nagar", 
            owner: "Ramesh Gupta", 
            phone: "9876543211", 
            address: "Main Road, Gandhi Nagar",
            lastOrder: "1 day ago",
            totalOrders: 32,
            status: "active"
        },
        { 
            id: 3, 
            name: "Bansal Provision", 
            area: "Shahdara", 
            owner: "Sunil Bansal", 
            phone: "9876543212", 
            address: "Near Bus Stand, Shahdara",
            lastOrder: "3 hours ago",
            totalOrders: 28,
            status: "active"
        },
        { 
            id: 4, 
            name: "Sharma Super Mart", 
            area: "Preet Vihar", 
            owner: "Rajesh Sharma", 
            phone: "9876543213", 
            address: "Block B, Preet Vihar",
            lastOrder: "2 days ago",
            totalOrders: 19,
            status: "active"
        },
        { 
            id: 5, 
            name: "Verma Departmental Store", 
            area: "Laxmi Nagar", 
            owner: "Anil Verma", 
            phone: "9876543214", 
            address: "Laxmi Nagar Main Market",
            lastOrder: "5 hours ago",
            totalOrders: 52,
            status: "active"
        }
    ],
    
    // Products (25-30 products)
    products: [
        { id: 1, name: "Aashirvaad Atta", price: 420, unit: "5 kg", image: "atta.jpg", category: "Groceries", stock: 150 },
        { id: 2, name: "Fortune Sunflower Oil", price: 210, unit: "1 liter", image: "oil.jpg", category: "Groceries", stock: 200 },
        { id: 3, name: "Tata Salt", price: 28, unit: "1 kg", image: "salt.jpg", category: "Groceries", stock: 500 },
        { id: 4, name: "Maggi Noodles", price: 70, unit: "Pack of 4", image: "maggi.jpg", category: "Food", stock: 300 },
        { id: 5, name: "Parle-G Biscuits", price: 50, unit: "300 gm", image: "biscuit.jpg", category: "Food", stock: 400 },
        { id: 6, name: "Surf Excel", price: 180, unit: "1 kg", image: "detergent.jpg", category: "Home Care", stock: 150 },
        { id: 7, name: "Colgate Toothpaste", price: 85, unit: "200 gm", image: "toothpaste.jpg", category: "Personal Care", stock: 250 },
        { id: 8, name: "Dairy Milk Chocolate", price: 100, unit: "150 gm", image: "chocolate.jpg", category: "Food", stock: 180 },
        { id: 9, name: "Red Label Tea", price: 300, unit: "500 gm", image: "tea.jpg", category: "Groceries", stock: 120 },
        { id: 10, name: "Pepsi", price: 90, unit: "2.25 liter", image: "pepsi.jpg", category: "Beverages", stock: 200 },
        { id: 11, name: "Amul Butter", price: 60, unit: "100 gm", image: "butter.jpg", category: "Dairy", stock: 180 },
        { id: 12, name: "Nescafe Coffee", price: 220, unit: "100 gm", image: "coffee.jpg", category: "Groceries", stock: 100 },
        { id: 13, name: "Lays Chips", price: 20, unit: "50 gm", image: "chips.jpg", category: "Food", stock: 350 },
        { id: 14, name: "Dettol Soap", price: 45, unit: "125 gm", image: "soap.jpg", category: "Personal Care", stock: 280 },
        { id: 15, name: "Harpic Toilet Cleaner", price: 120, unit: "500 ml", image: "harpic.jpg", category: "Home Care", stock: 150 },
        { id: 16, name: "Kellogg's Cornflakes", price: 180, unit: "500 gm", image: "cornflakes.jpg", category: "Food", stock: 90 },
        { id: 17, name: "Tata Tea Gold", price: 350, unit: "500 gm", image: "tea_gold.jpg", category: "Groceries", stock: 80 },
        { id: 18, name: "Patanjali Honey", price: 250, unit: "500 gm", image: "honey.jpg", category: "Food", stock: 110 },
        { id: 19, name: "Coca Cola", price: 95, unit: "2.25 liter", image: "coke.jpg", category: "Beverages", stock: 220 },
        { id: 20, name: "Vim Dishwash", price: 85, unit: "500 ml", image: "vim.jpg", category: "Home Care", stock: 160 },
        { id: 21, name: "Britannia Bread", price: 40, unit: "400 gm", image: "bread.jpg", category: "Food", stock: 200 },
        { id: 22, name: "Dabur Honey", price: 280, unit: "500 gm", image: "dabur_honey.jpg", category: "Food", stock: 95 },
        { id: 23, name: "MTR Sambar Powder", price: 120, unit: "200 gm", image: "sambar.jpg", category: "Groceries", stock: 140 },
        { id: 24, name: "Good Day Biscuits", price: 55, unit: "300 gm", image: "goodday.jpg", category: "Food", stock: 320 },
        { id: 25, name: "Lifebuoy Soap", price: 38, unit: "125 gm", image: "lifebuoy.jpg", category: "Personal Care", stock: 400 }
    ],
    
    // Orders
    orders: [
        { 
            id: 1001, 
            orderNumber: "ORD00123", 
            date: "2024-01-15", 
            amount: 2450, 
            status: "Delivered", 
            items: 8,
            shop: "Mohan Kirana Store",
            deliveryDate: "2024-01-15"
        },
        { 
            id: 1002, 
            orderNumber: "ORD00124", 
            date: "2024-01-14", 
            amount: 1870, 
            status: "Processing", 
            items: 6,
            shop: "Gupta General Store",
            deliveryDate: "2024-01-16"
        },
        { 
            id: 1003, 
            orderNumber: "ORD00125", 
            date: "2024-01-12", 
            amount: 3200, 
            status: "Delivered", 
            items: 12,
            shop: "Bansal Provision",
            deliveryDate: "2024-01-13"
        },
        { 
            id: 1004, 
            orderNumber: "ORD00126", 
            date: "2024-01-10", 
            amount: 1450, 
            status: "Pending", 
            items: 5,
            shop: "Sharma Super Mart",
            deliveryDate: "2024-01-11"
        }
    ],
    
    // Shop Owner Data
    shopOwner: {
        name: "Mohan Singh",
        shopName: "Mohan Kirana Store",
        email: "shop@demo.com",
        phone: "9876543210",
        address: "Shop No. 12, Gandhi Nagar Market, Delhi",
        walletBalance: 800,
        totalOrders: 45,
        memberSince: "2023-06-15"
    },
    
    // Salesperson Data
    salesperson: {
        name: "Rajesh Kumar",
        employeeId: "EMP001",
        assignedArea: "Gandhi Nagar",
        phone: "9876543200",
        totalSales: 125000,
        todayOrders: 8,
        pendingDeliveries: 3
    },
    
    // Admin Dashboard Data
    adminStats: {
        totalShops: 48,
        activeSalespersons: 8,
        totalProducts: 25,
        pendingOrders: 15,
        monthlyRevenue: 320000,
        todayRevenue: 12500
    },
    
    // Wallet Transactions
    walletTransactions: [
        { id: 1, type: "signup_bonus", amount: 500, date: "2023-06-15", description: "Signup Bonus" },
        { id: 2, type: "referral_bonus", amount: 300, date: "2023-08-20", description: "Referral Bonus - Anil Store" },
        { id: 3, type: "order_discount", amount: -150, date: "2024-01-10", description: "Order #ORD00120 Discount" },
        { id: 4, type: "order_discount", amount: -200, date: "2024-01-15", description: "Order #ORD00123 Discount" }
    ],
    
    // Referrals
    referrals: [
        { id: 1, name: "Anil Store", date: "2023-08-20", status: "active", bonus: 300 },
        { id: 2, name: "Sunil Mart", date: "2023-09-15", status: "active", bonus: 200 }
    ]
};

// Cart Management
let currentCart = [];
// let selectedShop = null;
// let selectedArea = null;

// Helper Functions
function getCartTotal() {
    return currentCart.reduce((total, item) => total + (item.price * item.quantity), 0);
}

function getCartCount() {
    return currentCart.reduce((count, item) => count + item.quantity, 0);
}

function addToCart(productId, quantity = 1) {
    const product = dummyData.products.find(p => p.id === productId);
    if (product) {
        const existingItem = currentCart.find(item => item.id === productId);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            currentCart.push({
                ...product,
                quantity: quantity,
                total: product.price * quantity
            });
        }
        saveCart();
    }
}

function removeFromCart(productId) {
    currentCart = currentCart.filter(item => item.id !== productId);
    saveCart();
}

function updateCartQuantity(productId, quantity) {
    const item = currentCart.find(item => item.id === productId);
    if (item) {
        if (quantity <= 0) {
            removeFromCart(productId);
        } else {
            item.quantity = quantity;
            item.total = item.price * quantity;
            saveCart();
        }
    }
}

function clearCart() {
    currentCart = [];
    saveCart();
}

function saveCart() {
    localStorage.setItem('vamika_cart', JSON.stringify(currentCart));
}

function loadCart() {
    const savedCart = localStorage.getItem('vamika_cart');
    if (savedCart) {
        currentCart = JSON.parse(savedCart);
    }
}

// Order Management
function createOrder(shopId, areaId, items, notes = '') {
    const shop = dummyData.shops.find(s => s.id === shopId);
    const area = dummyData.areas.find(a => a.id === areaId);
    
    const orderNumber = 'ORD' + Date.now().toString().slice(-6);
    const subtotal = items.reduce((sum, item) => sum + item.total, 0);
    const deliveryCharge = area ? area.deliveryCharge : 50;
    const total = subtotal + deliveryCharge;
    
    const order = {
        id: Date.now(),
        orderNumber: orderNumber,
        shop: shop,
        area: area,
        items: items,
        subtotal: subtotal,
        delivery: deliveryCharge,
        total: total,
        notes: notes,
        date: new Date().toISOString().split('T')[0],
        time: new Date().toLocaleTimeString(),
        status: 'pending',
        paymentMethod: 'cash',
        paymentStatus: 'pending'
    };
    
    // Save to localStorage for demo
    const orders = JSON.parse(localStorage.getItem('vamika_orders') || '[]');
    orders.push(order);
    localStorage.setItem('vamika_orders', JSON.stringify(orders));
    
    return order;
}

// Initialize
loadCart();