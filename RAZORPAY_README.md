# Razorpay Payment Integration

This Laravel application includes Razorpay payment integration for purchasing courses.

## Setup

1. Install Razorpay PHP SDK:
   ```bash
   composer require razorpay/razorpay
   ```

2. Add your Razorpay credentials to `.env`:
   ```
   RAZORPAY_KEY=your_razorpay_key_here
   RAZORPAY_SECRET=your_razorpay_secret_here
   ```

3. Run migrations:
   ```bash
   php artisan migrate
   ```

## API Endpoints

All endpoints require authentication.

### Create Payment Order
- **POST** `/payments/create-order`
- **Body**: `{"course_id": 1}`
- **Response**: Order details with Razorpay order ID and key

### Verify Payment
- **POST** `/payments/verify`
- **Body**:
  ```json
  {
    "razorpay_payment_id": "pay_xxx",
    "razorpay_order_id": "order_xxx",
    "razorpay_signature": "signature_xxx",
    "payment_id": 1
  }
  ```
- **Response**: Success message and enrollment confirmation

### Payment History
- **GET** `/payments/history`
- **Response**: List of user's payments

### Available Courses
- **GET** `/payments/available-courses`
- **Response**: Courses available for purchase (not already enrolled)

## Frontend Integration

1. Load Razorpay checkout script:
   ```html
   <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
   ```

2. Create order via API, then initialize Razorpay:
   ```javascript
   const options = {
     key: response.key,
     amount: response.amount,
     currency: response.currency,
     order_id: response.order_id,
     name: 'Engineers Clinic',
     description: 'Course Purchase',
     handler: function (response) {
       // Verify payment
       fetch('/payments/verify', {
         method: 'POST',
         headers: { 'Content-Type': 'application/json' },
         body: JSON.stringify({
           razorpay_payment_id: response.razorpay_payment_id,
           razorpay_order_id: response.razorpay_order_id,
           razorpay_signature: response.razorpay_signature,
           payment_id: response.payment_id
         })
       });
     }
   };
   const rzp = new Razorpay(options);
   rzp.open();
   ```

## Database Changes

The `payments` table includes:
- `razorpay_order_id`: Razorpay order ID
- `razorpay_payment_id`: Razorpay payment ID

## Security Notes

- All payments are verified using Razorpay signature verification
- Users can only pay for courses they haven't already enrolled in
- Payment status is tracked and updated appropriately
