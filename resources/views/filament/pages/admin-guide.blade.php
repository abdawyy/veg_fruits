<div class="fi-section-content-ctn space-y-8 text-sm leading-relaxed text-gray-700 dark:text-gray-200">
    <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <h2 class="text-base font-semibold text-gray-950 dark:text-white">Products &amp; special services</h2>
        <ul class="mt-3 list-disc space-y-2 ps-5">
            <li><strong>Products</strong> — sidebar group <em>Catalog</em> → add/edit SKUs, bilingual names, prices, image URL, and per-product <strong>preparation services</strong> &amp; <strong>packaging</strong> (checkboxes).</li>
            <li><strong>Preparation services</strong> — catalog-wide definitions (wash, peel, …) and surcharges.</li>
            <li><strong>Packaging types</strong> — bag / tray / box fees applied when customers pick packaging at checkout.</li>
        </ul>
    </section>

    <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <h2 class="text-base font-semibold text-gray-950 dark:text-white">Staff roles &amp; permissions</h2>
        <ul class="mt-3 list-disc space-y-2 ps-5">
            <li><strong>Roles</strong> — sidebar <em>Shield</em> → <strong>Roles</strong> (<code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">/admin/shield/roles</code>). Create or edit roles and tick which resources, pages, and widgets each role may use.</li>
            <li><strong>Super admin</strong> — role <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">super_admin</code> has every permission (full access).</li>
            <li><strong>Starter roles</strong> (from seed): <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">orders_manager</code>, <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">catalog_manager</code>, <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">content_manager</code>, <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">analytics_viewer</code>.</li>
            <li><strong>Assign to staff</strong> — <em>Customers</em> → <strong>Users</strong> → edit a user: enable <strong>Admin panel access</strong> and pick one or more roles. Without a role, an admin user cannot open the panel.</li>
        </ul>
    </section>

    <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <h2 class="text-base font-semibold text-gray-950 dark:text-white">Customers &amp; orders</h2>
        <ul class="mt-3 list-disc space-y-2 ps-5">
            <li><strong>Users</strong> — group <em>Customers</em> → list with email, phone, order count. Open a user to see their <strong>Orders</strong> tab.</li>
            <li><strong>Orders</strong> — group <em>Orders</em> → reference, linked account email, guest email, phone, delivery city, totals. Edit an order for notes/status; open the <strong>Line items</strong> tab for basket contents. Use <strong>Export / Import Excel</strong> on the list page.</li>
        </ul>
    </section>

    <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <h2 class="text-base font-semibold text-gray-950 dark:text-white">Shipping cities</h2>
        <p class="mt-2">Group <em>Shipping</em> → <strong>Cities &amp; fees</strong>. Each city has a delivery fee (EGP) used at checkout. Excel import/export is on the cities list page.</p>
    </section>

    <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <h2 class="text-base font-semibold text-gray-950 dark:text-white">Analytics &amp; insights (built in)</h2>
        <p class="mt-2">Open <strong>Dashboard</strong>. You get:</p>
        <ul class="mt-3 list-disc space-y-2 ps-5">
            <li><strong>Traffic</strong> — active sessions, today’s sessions, totals, page views (7d), referrer &amp; UTM counts, mobile today.</li>
            <li><strong>Commerce snapshot (7d)</strong> — orders, new sessions, page views, cart URL hits, rough order÷session %.</li>
            <li><strong>Charts</strong> — orders over time, top storefront paths, traffic sources (referrer host), most viewed products (product page counter).</li>
            <li><strong>Site visitors</strong> — menu entry lists sessions with entry path, last path, referrer, UTM, device. Export visitors or <strong>page views (Excel)</strong> from that screen.</li>
        </ul>
        <p class="mt-4 font-medium text-gray-950 dark:text-white">Optional: Google Analytics 4 &amp; Meta Pixel on the public shop</p>
        <p class="mt-2">Set in <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">.env</code>: <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">ANALYTICS_GA4_MEASUREMENT_ID=G-…</code> and/or <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">ANALYTICS_META_PIXEL_ID=</code> (numeric). Scripts load on the storefront and login/register layout only — not inside <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">/admin</code>.</p>
        <p class="mt-4 font-medium text-gray-950 dark:text-white">Not included (would need extra services or dev)</p>
        <ul class="mt-2 list-disc space-y-1 ps-5 text-gray-600 dark:text-gray-400">
            <li>Heatmaps / session replay (e.g. Hotjar, Microsoft Clarity).</li>
            <li>Geo map by city/country (we only store hashed IP for privacy).</li>
            <li>Per-step funnel beyond “cart URL” (e.g. add-to-cart events as rows).</li>
            <li>Google Ads / Search Console integration.</li>
            <li>Real-time push to Slack/Email on traffic spikes.</li>
        </ul>
    </section>

    <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <h2 class="text-base font-semibold text-gray-950 dark:text-white">Storefront, cart &amp; customer area</h2>
        <ul class="mt-3 list-disc space-y-2 ps-5">
            <li><strong>Public shop</strong> — customers browse the catalog, use the theme toggle, and add items to a <strong>session cart</strong> (<code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">/cart</code>) with checkout.</li>
            <li><strong>Sign in</strong> — <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">/login</code> and <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">/register</code> for storefront accounts.</li>
            <li><strong>Admin panel</strong> — <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">/admin</code> (staff with <strong>Admin panel access</strong> and at least one role).</li>
            <li><strong>My account</strong> — <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">/my</code> — signed-in customers see profile and <strong>My orders</strong>.</li>
        </ul>
    </section>

    <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <h2 class="text-base font-semibold text-gray-950 dark:text-white">Storefront banners</h2>
        <p class="mt-2">Group <em>Storefront</em> → <strong>Home banners</strong> for carousel slides (multiple active banners rotate on the home page). Use <strong>Create</strong> for one slide, <strong>Create multiple</strong> for several at once, or Excel import (leave <code class="rounded bg-black/5 px-1 py-0.5 dark:bg-white/10">id</code> empty for new rows). Set <strong>sort order</strong> to control slide sequence.</p>
    </section>
</div>
