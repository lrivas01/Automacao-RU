import axios from 'axios';
import React, { useState } from 'react';

interface Student {
    name: string;
    ra: number;
}

interface DashboardProps {
    student: Student;
}

export default function Dashboard({ student }: DashboardProps) {
    const [tokens, setTokens] = useState(1);
    const [paymentMethod, setPaymentMethod] = useState('pix');
    const [message, setMessage] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [qrCode, setQrCode] = useState<string | null>(null);
    const [paymentKey, setPaymentKey] = useState<string | null>(null);
    const [cardData, setCardData] = useState({
        number: '',
        expiry: '',
        cvv: '',
    });

    const handlePayment = async (e: React.FormEvent) => {
        e.preventDefault();
        setShowModal(true);

        try {
            const response = await axios.post('/api/payments', {
                tokens,
                payment_method: paymentMethod,
            });

            if (paymentMethod === 'pix') {
                setQrCode(response.data.qr_code);
                setPaymentKey(response.data.payment_key);
            } else {
                setQrCode(null);
                setPaymentKey(null);
            }

            setMessage(response.data.message);
        } catch (error) {
            setMessage('Erro ao processar o pagamento.');
        }
    };

    const handleCardChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setCardData({ ...cardData, [e.target.name]: e.target.value });
    };

    return (
        <div className="p-4">
            <h1 className="text-xl font-bold">Dashboard</h1>
            <p>{student.name}</p>
            <p>RA: {student.ra}</p>

            <form onSubmit={handlePayment} className="mt-4 space-y-3">
                <div>
                    <label htmlFor="tokens" className="block">
                        Quantidade de Fichas:
                    </label>
                    <input
                        type="number"
                        id="tokens"
                        value={tokens}
                        onChange={(e) => setTokens(Number(e.target.value))}
                        min="1"
                        required
                        className="rounded border p-1"
                    />
                </div>

                <div>
                    <label htmlFor="paymentMethod" className="block">
                        Método de Pagamento:
                    </label>
                    <select
                        id="paymentMethod"
                        value={paymentMethod}
                        onChange={(e) => setPaymentMethod(e.target.value)}
                        required
                        className="rounded border p-1"
                    >
                        <option value="pix">PIX</option>
                        <option value="card">Cartão</option>
                    </select>
                </div>

                <button
                    type="submit"
                    className="rounded bg-blue-500 px-4 py-2 text-white"
                >
                    Comprar ficha
                </button>
            </form>

            {message && <p className="mt-2 text-green-600">{message}</p>}

            {/* Modal de pagamento */}
            {showModal && (
                <div className="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
                    <div className="w-96 rounded bg-white p-5 shadow-lg">
                        <h2 className="mb-3 text-lg font-bold">Pagamento</h2>
                        <p>
                            Quantidade de Fichas: <strong>{tokens}</strong>
                        </p>
                        <p>
                            Total a pagar: <strong>R${tokens * 4},00</strong>
                        </p>

                        {paymentMethod === 'pix' ? (
                            <div className="mt-4 text-center">
                                <p>Escaneie o QR Code ou copie a chave PIX:</p>
                                {qrCode && (
                                    <img
                                        src={qrCode}
                                        alt="QR Code PIX"
                                        className="mx-auto my-2"
                                    />
                                )}
                                {paymentKey && (
                                    <p className="rounded bg-gray-200 p-2">
                                        {paymentKey}
                                    </p>
                                )}
                            </div>
                        ) : (
                            <div className="mt-4 space-y-2">
                                <label>Número do Cartão:</label>
                                <input
                                    type="text"
                                    name="number"
                                    value={cardData.number}
                                    onChange={handleCardChange}
                                    className="w-full rounded border p-1"
                                />
                                <label>Validade (MM/AA):</label>
                                <input
                                    type="text"
                                    name="expiry"
                                    value={cardData.expiry}
                                    onChange={handleCardChange}
                                    className="w-full rounded border p-1"
                                />
                                <label>CVV:</label>
                                <input
                                    type="text"
                                    name="cvv"
                                    value={cardData.cvv}
                                    onChange={handleCardChange}
                                    className="w-full rounded border p-1"
                                />
                            </div>
                        )}

                        <button
                            onClick={() => setShowModal(false)}
                            className="mt-4 w-full rounded bg-red-500 px-4 py-2 text-white"
                        >
                            Fechar
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
}
