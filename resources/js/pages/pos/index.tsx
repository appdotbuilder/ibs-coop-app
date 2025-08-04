import { AppShell } from '@/components/app-shell';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

interface Product {
    id: number;
    sku: string;
    name: string;
    category: string;
    selling_price: number;
    member_price?: number;
    stock_quantity: number;
    unit: string;
    points_earned: number;
    allow_installment: boolean;
}

interface Member {
    id: number;
    member_id: string;
    name: string;
    email: string;
    points: number;
}

interface CartItem {
    product: Product;
    quantity: number;
    unit_price: number;
    total_price: number;
}

interface PosIndexProps {
    products: Product[];
    members: Member[];
    [key: string]: unknown;
}

export default function PosIndex({ products, members }: PosIndexProps) {
    const [cart, setCart] = useState<CartItem[]>([]);
    const [selectedMember, setSelectedMember] = useState<Member | null>(null);
    const [paymentMethod, setPaymentMethod] = useState<'cash' | 'transfer' | 'credit' | 'installment'>('cash');
    const [discountAmount, setDiscountAmount] = useState(0);
    const [pointsUsed, setPointsUsed] = useState(0);
    const [installmentCount, setInstallmentCount] = useState(3);
    const [downPayment, setDownPayment] = useState(0);
    const [searchTerm, setSearchTerm] = useState('');
    const [selectedCategory, setSelectedCategory] = useState('');

    // Filter products
    const filteredProducts = products.filter(product => {
        const matchesSearch = product.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                            product.sku.toLowerCase().includes(searchTerm.toLowerCase());
        const matchesCategory = !selectedCategory || product.category === selectedCategory;
        return matchesSearch && matchesCategory && product.stock_quantity > 0;
    });

    // Get unique categories
    const categories = Array.from(new Set(products.map(p => p.category)));

    // Calculate totals
    const subtotal = cart.reduce((sum, item) => sum + item.total_price, 0);
    const pointsDiscount = Math.min(pointsUsed, subtotal);
    const finalTotal = Math.max(0, subtotal - discountAmount - pointsDiscount);

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    };

    const addToCart = (product: Product) => {
        const price = selectedMember && product.member_price ? product.member_price : product.selling_price;
        const existingItem = cart.find(item => item.product.id === product.id);

        if (existingItem) {
            if (existingItem.quantity < product.stock_quantity) {
                setCart(cart.map(item =>
                    item.product.id === product.id
                        ? { ...item, quantity: item.quantity + 1, total_price: (item.quantity + 1) * price }
                        : item
                ));
            }
        } else {
            setCart([...cart, {
                product,
                quantity: 1,
                unit_price: price,
                total_price: price
            }]);
        }
    };

    const updateQuantity = (productId: number, quantity: number) => {
        if (quantity <= 0) {
            removeFromCart(productId);
            return;
        }

        setCart(cart.map(item =>
            item.product.id === productId
                ? { ...item, quantity, total_price: quantity * item.unit_price }
                : item
        ));
    };

    const removeFromCart = (productId: number) => {
        setCart(cart.filter(item => item.product.id !== productId));
    };

    const clearCart = () => {
        setCart([]);
        setSelectedMember(null);
        setDiscountAmount(0);
        setPointsUsed(0);
        setPaymentMethod('cash');
    };

    const processTransaction = () => {
        if (cart.length === 0) return;

        const transactionData = {
            items: cart.map(item => ({
                product_id: item.product.id,
                quantity: item.quantity,
                unit_price: item.unit_price
            })),
            member_id: selectedMember?.id,
            payment_method: paymentMethod,
            discount_amount: discountAmount,
            points_used: pointsUsed,
        };

        if (paymentMethod === 'installment') {
            const installmentData = transactionData as typeof transactionData & {
                installment_count: number;
                down_payment: number;
            };
            installmentData.installment_count = installmentCount;
            installmentData.down_payment = downPayment;
        }

        router.post(route('pos.store'), transactionData, {
            onSuccess: () => {
                clearCart();
            }
        });
    };

    return (
        <AppShell>
            <Head title="Point of Sale" />
            
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {/* Products Section */}
                <div className="lg:col-span-2 space-y-6">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            🛒 Point of Sale
                        </h1>
                        <p className="text-gray-600 dark:text-gray-400">
                            Select products to add to cart
                        </p>
                    </div>

                    {/* Product Filters */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input
                                type="text"
                                placeholder="Search products..."
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                            />
                            <select
                                value={selectedCategory}
                                onChange={(e) => setSelectedCategory(e.target.value)}
                                className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">All Categories</option>
                                {categories.map(category => (
                                    <option key={category} value={category}>{category}</option>
                                ))}
                            </select>
                        </div>
                    </div>

                    {/* Products Grid */}
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        {filteredProducts.map((product) => (
                            <div key={product.id} className="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                                <div className="flex justify-between items-start mb-2">
                                    <h3 className="text-sm font-medium text-gray-900 dark:text-white line-clamp-2">
                                        {product.name}
                                    </h3>
                                    <span className="text-xs text-gray-500 dark:text-gray-400">
                                        {product.stock_quantity} {product.unit}
                                    </span>
                                </div>
                                <p className="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                    {product.sku} • {product.category}
                                </p>
                                <div className="flex justify-between items-center">
                                    <div>
                                        <p className="text-sm font-semibold text-gray-900 dark:text-white">
                                            {formatCurrency(selectedMember && product.member_price ? product.member_price : product.selling_price)}
                                        </p>
                                        {selectedMember && product.member_price && (
                                            <p className="text-xs text-green-600 dark:text-green-400">
                                                Member Price
                                            </p>
                                        )}
                                    </div>
                                    <button
                                        onClick={() => addToCart(product)}
                                        disabled={product.stock_quantity === 0}
                                        className="px-3 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        ➕ Add
                                    </button>
                                </div>
                                {product.points_earned > 0 && (
                                    <p className="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                                        ⭐ Earn {product.points_earned} points
                                    </p>
                                )}
                            </div>
                        ))}
                    </div>

                    {filteredProducts.length === 0 && (
                        <div className="text-center py-12">
                            <div className="text-gray-500 dark:text-gray-400">
                                <div className="text-4xl mb-4">📦</div>
                                <h3 className="text-lg font-medium">No products found</h3>
                                <p className="mt-2">Try adjusting your search or filters.</p>
                            </div>
                        </div>
                    )}
                </div>

                {/* Cart Section */}
                <div className="space-y-6">
                    {/* Member Selection */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            👤 Customer
                        </h3>
                        <select
                            value={selectedMember?.id || ''}
                            onChange={(e) => {
                                const member = members.find(m => m.id === Number(e.target.value));
                                setSelectedMember(member || null);
                                setPointsUsed(0); // Reset points when changing member
                            }}
                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">Walk-in Customer</option>
                            {members.map(member => (
                                <option key={member.id} value={member.id}>
                                    {member.name} ({member.member_id}) - ⭐ {member.points}
                                </option>
                            ))}
                        </select>
                    </div>

                    {/* Cart Items */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div className="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div className="flex justify-between items-center">
                                <h3 className="text-lg font-medium text-gray-900 dark:text-white">
                                    🛒 Cart ({cart.length})
                                </h3>
                                {cart.length > 0 && (
                                    <button
                                        onClick={clearCart}
                                        className="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm"
                                    >
                                        🗑️ Clear
                                    </button>
                                )}
                            </div>
                        </div>
                        <div className="p-6">
                            {cart.length === 0 ? (
                                <p className="text-gray-500 dark:text-gray-400 text-center py-4">
                                    Cart is empty
                                </p>
                            ) : (
                                <div className="space-y-4">
                                    {cart.map((item) => (
                                        <div key={item.product.id} className="flex items-center justify-between">
                                            <div className="flex-1">
                                                <p className="text-sm font-medium text-gray-900 dark:text-white">
                                                    {item.product.name}
                                                </p>
                                                <p className="text-xs text-gray-500 dark:text-gray-400">
                                                    {formatCurrency(item.unit_price)} each
                                                </p>
                                            </div>
                                            <div className="flex items-center space-x-2">
                                                <button
                                                    onClick={() => updateQuantity(item.product.id, item.quantity - 1)}
                                                    className="w-6 h-6 flex items-center justify-center rounded bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-500"
                                                >
                                                    -
                                                </button>
                                                <span className="text-sm font-medium text-gray-900 dark:text-white w-8 text-center">
                                                    {item.quantity}
                                                </span>
                                                <button
                                                    onClick={() => updateQuantity(item.product.id, item.quantity + 1)}
                                                    disabled={item.quantity >= item.product.stock_quantity}
                                                    className="w-6 h-6 flex items-center justify-center rounded bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-500 disabled:opacity-50"
                                                >
                                                    +
                                                </button>
                                                <button
                                                    onClick={() => removeFromCart(item.product.id)}
                                                    className="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 ml-2"
                                                >
                                                    🗑️
                                                </button>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Payment Details */}
                    {cart.length > 0 && (
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                💳 Payment
                            </h3>
                            
                            <div className="space-y-4">
                                {/* Payment Method */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Payment Method
                                    </label>
                                    <select
                                        value={paymentMethod}
                                        onChange={(e) => setPaymentMethod(e.target.value as 'cash' | 'transfer' | 'credit' | 'installment')}
                                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                    >
                                        <option value="cash">Cash</option>
                                        <option value="transfer">Bank Transfer</option>
                                        <option value="credit">Credit</option>
                                        {selectedMember && (
                                            <option value="installment">Installment</option>
                                        )}
                                    </select>
                                </div>

                                {/* Discount */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Discount Amount
                                    </label>
                                    <input
                                        type="number"
                                        value={discountAmount}
                                        onChange={(e) => setDiscountAmount(Number(e.target.value))}
                                        min="0"
                                        max={subtotal}
                                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                    />
                                </div>

                                {/* Points */}
                                {selectedMember && selectedMember.points > 0 && (
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Use Points (Available: {selectedMember.points})
                                        </label>
                                        <input
                                            type="number"
                                            value={pointsUsed}
                                            onChange={(e) => setPointsUsed(Math.min(Number(e.target.value), selectedMember.points, subtotal))}
                                            min="0"
                                            max={Math.min(selectedMember.points, subtotal)}
                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                        />
                                    </div>
                                )}

                                {/* Installment Options */}
                                {paymentMethod === 'installment' && (
                                    <>
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Number of Installments
                                            </label>
                                            <select
                                                value={installmentCount}
                                                onChange={(e) => setInstallmentCount(Number(e.target.value))}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                            >
                                                {[3, 6, 12, 18, 24].map(months => (
                                                    <option key={months} value={months}>{months} months</option>
                                                ))}
                                            </select>
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Down Payment
                                            </label>
                                            <input
                                                type="number"
                                                value={downPayment}
                                                onChange={(e) => setDownPayment(Number(e.target.value))}
                                                min="0"
                                                max={finalTotal}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                            />
                                        </div>
                                    </>
                                )}

                                {/* Totals */}
                                <div className="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2">
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                        <span className="text-gray-900 dark:text-white">{formatCurrency(subtotal)}</span>
                                    </div>
                                    {discountAmount > 0 && (
                                        <div className="flex justify-between text-sm">
                                            <span className="text-gray-600 dark:text-gray-400">Discount:</span>
                                            <span className="text-red-600 dark:text-red-400">-{formatCurrency(discountAmount)}</span>
                                        </div>
                                    )}
                                    {pointsUsed > 0 && (
                                        <div className="flex justify-between text-sm">
                                            <span className="text-gray-600 dark:text-gray-400">Points Used:</span>
                                            <span className="text-yellow-600 dark:text-yellow-400">-{formatCurrency(pointsUsed)}</span>
                                        </div>
                                    )}
                                    <div className="flex justify-between text-lg font-semibold border-t border-gray-200 dark:border-gray-700 pt-2">
                                        <span className="text-gray-900 dark:text-white">Total:</span>
                                        <span className="text-gray-900 dark:text-white">{formatCurrency(finalTotal)}</span>
                                    </div>
                                </div>

                                <button
                                    onClick={processTransaction}
                                    disabled={cart.length === 0 || finalTotal < 0}
                                    className="w-full px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                >
                                    💳 Process Payment
                                </button>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AppShell>
    );
}