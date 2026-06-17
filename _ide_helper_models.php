<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\InvoicetemplatesModel
 *
 * @property int $id
 * @property int $subscriber_id
 * @property string|null $name
 * @property bool $is_active
 * @property string|null $logo_path
 * @property string|null $primary_color
 * @property string|null $secondary_color
 * @property string|null $font_family
 * @property array|null $layout
 * @property array|null $fields_config
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel whereFieldsConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel whereFontFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel wherePrimaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel whereSecondaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel whereSubscriberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoicetemplatesModel whereUpdatedAt($value)
 */
	class InvoicetemplatesModel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

