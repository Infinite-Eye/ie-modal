# Modal Framework

Libraries Used:
* jquery-modal https://jquerymodal.com/
* js-cookie https://github.com/js-cookie/js-cookie/tree/latest

## Installation

Add dependencies to composer.json file, and install with composer.json
```
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/Infinite-Eye/modal.git"
    }
  ],
  "require": {
    "infinite-eye/modal": "dev-main"
  }
}
```

## Example adding WordPress Theme Modal

```php
// Add cookie
\InfiniteEye\Modal\Plugin::instance()->create('promo_25_off')
    // content to be displayed
    ->content("<h1><span>25% OFF</span> DRAUGHT BEER AND <span>FREE SHIPPING</span> SITE WIDE</h1>
    <p>(OFFER AVAILABLE 24TH APR - 1ST MAY)</p>")
    // conditions to display modal
    ->trigger(function () {
        return is_shop() || is_product_category() || is_product();
    })
    // overwrite arguments passed to modal() https://jquerymodal.com/
    ->plugin_args([
        'clickClose' => false,
        'showClose' => false
    ])
    // set cookie to expire after 1 day, 'yes' is the value set in the cookie
    ->cookie(1, 'yes')
    ->schedule_from(strtotime('24th April 2023 00:00:01'))
    ->schedule_to(strtotime('1st May 2023 23:59:59'));

```

## Overwrite PHP Template

Basic modal template can be overridden by coppying templates from templates folder to theme directory:

```
theme-name/template-parts/modal/modal-open.php
theme-name/template-parts/modal/modal-close.php
```
