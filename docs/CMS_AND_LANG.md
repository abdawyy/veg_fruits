# CMS vs language files

## Use `lang/en/aldawy.php` and `lang/ar/aldawy.php` when

- The string is **fixed UI chrome** (buttons, nav, form labels, validation messages).
- Translators work in PHP/lang files and deploy with code.
- The same key is used in Blade, Livewire, Filament (via `__()`), and emails.

## Use **Content strings** (Filament → Content) when

- Marketing or legal copy changes **often** without a deploy.
- Non-developers edit text in admin.
- Keys are referenced in Blade via `Cms::text('hero_title', 'fallback')` or similar.

## Use **SEO settings** / **Home banners** when

- The content is structural (meta defaults, carousel slides) with its own admin resource.

## Rule of thumb

| Need | Location |
|------|----------|
| “Add to cart” | Lang |
| Seasonal hero headline ops can edit weekly | CMS or Home banners |
| Product name | Database (`products.name` JSON), not lang |
| Invoice label “Subtotal” | Lang |

Do not duplicate the same phrase in CMS and lang; pick one source of truth.
