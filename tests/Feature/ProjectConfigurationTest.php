<?php

test('project uses the approved Indonesian store defaults', function () {
    expect(config('app.timezone'))->toBe('Asia/Jakarta')
        ->and(config('app.locale'))->toBe('id')
        ->and(config('store.currency'))->toBe('IDR')
        ->and(config('store.inventory.quantity_scale'))->toBe(4)
        ->and(config('store.inventory.allow_negative_stock'))->toBeFalse()
        ->and(config('store.documents.max_kilobytes'))->toBe(8192)
        ->and(config('store.documents.max_files_per_transaction'))->toBe(5);
});
