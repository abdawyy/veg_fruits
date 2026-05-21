# Order packaging fees vs line surcharges

## Line-level amounts (most checkout orders)

Each `order_items` row stores:

- **Unit price × quantity** — base produce price.
- **Preparation & packaging surcharges** — rolled into `services` JSON and included in the line total via `servicesSurchargeTotal` when the order is built from the cart.

These surcharges are **per product line**, chosen on the product page or cart.

## `orders.packaging_fee` (order-level field)

`CreateOrderAction` sets `packaging_fee` from `CalculateCartTotalService`, which adds a single **order-level** packaging fee passed in `CreateOrderPayload::packagingFee`.

Today the public storefront passes **`0`** — all packaging/preparation fees live on lines.

Use `orders.packaging_fee` when you intentionally add a **flat order-wide** packaging charge (e.g. admin-created orders or a future “gift wrap entire order” option), not for per-item add-ons.

## Admin UI

On **Orders → Edit**, `packaging_fee` and `subtotal` are read-only snapshots from checkout. Changing line items does not recalculate them; edit status/notes or create adjustments in a future feature if needed.
