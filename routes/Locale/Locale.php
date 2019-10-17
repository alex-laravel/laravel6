<?php

/**
 * Locale Controllers
 */

Route::get('/locale/{locale}', 'LocaleController@swap')->name('swap');