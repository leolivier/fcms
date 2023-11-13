<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Poll;
use App\Models\PollOption;

class Install400 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default('0');
            $table->rememberToken();
            $table->smallInteger('access')->default(3);
            $table->string('displayname')->nullable();
            $table->date('birthday')->nullable();
            $table->string('token')->nullable();
            $table->string('avatar', 25)->default('no_avatar.jpg');
            $table->string('bio', 200)->nullable();
            $table->char('activate_code', 13)->nullable();
            $table->boolean('activated')->default(false);
            $table->boolean('login_attempts')->default(false);
            $table->dateTime('locked')->nullable();
            $table->dateTime('activity')->nullable();
            $table->timestamps();
        });

        $user = new User();

        $user->access = 10;
        $user->email  = 'noreply@domain.com';
        $user->name   = 'system';
        $user->save();

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->char('country', 2)->nullable();
            $table->string('address', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('home', 20)->nullable();
            $table->string('work', 20)->nullable();
            $table->string('cell', 20)->nullable();
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('alert', 50);
            $table->foreignId('user_id');
            $table->boolean('hide');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time_start')->nullable();
            $table->time('time_end')->nullable();
            $table->string('title', 50);
            $table->text('desc')->nullable();
            $table->foreignId('event_category_id');
            $table->string('repeat', 20)->nullable();
            $table->boolean('private')->default(false);
            $table->boolean('invite')->default(false);
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        $data = [
            [
                'date'  => '2007-01-01',
                'title' => __('New Year\'s Day'),
            ],
            [
                'date'  => '2007-02-02',
                'title' => __('Groundhog Day'),
            ],
            [
                'date'  => '2007-02-14',
                'title' => __('Valentine\'s Day'),
            ],
            [
                'date'  => '2007-03-17',
                'title' => __('St. Patrick\'s Day'),
            ],
            [
                'date'  => '2007-04-01',
                'title' => __('April Fools\' Day'),
            ],
            [
                'date'  => '2007-07-04',
                'title' => __('Independence Day'),
            ],
            [
                'date'  => '2007-10-31',
                'title' => __('Halloween'),
            ],
            [
                'date'  => '2007-11-11',
                'title' => __('Veterans Day'),
            ],
            [
                'date'  => '2007-12-25',
                'title' => __('Christmas'),
            ]
        ];
        foreach ($data as $d)
        {
            $event = new Event();
 
            $event->date              = $d['date'];
            $event->title             = $d['title'];
            $event->event_category_id = 4;
            $event->repeat            = 'yearly';
            $event->created_user_id   = 1;
            $event->updated_user_id   = 1;
            $event->save();
        }

        Schema::create('event_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('color', 20)->nullable();
            $table->string('description')->nullable();
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        // https://dribbble.com/shots/19770670-Dashboard-Calendar
        $data = [
            [
                'name'  => 'default',
                'color' => '#555555',
            ],
            [
                'name'  => __('Anniversary'),
                'color' => '#af85ee',
            ],
            [
                'name'  => __('Birthday'),
                'color' => '#fd764d',
            ],
            [
                'name'  => __('Holiday'),
                'color' => '#8bc48a'
            ]
        ];
        foreach ($data as $d)
        {
            $category = new EventCategory();
 
            $category->name            = $d['name'];
            $category->color           = $d['color'];
            $category->created_user_id = 1;
            $category->updated_user_id = 1;
            $category->save();
        }

        Schema::create('user_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('table', 50);
            $table->string('column', 50);
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('mime')->default('application/download');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('discussions', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->smallInteger('views')->default(0);
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('discussion_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discussion_id');
            $table->text('comments');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('photo_album_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_album_id');
            $table->text('comments');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('photo_albums', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description')->nullable();
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('external_photos', function (Blueprint $table) {
            $table->id();
            $table->string('source_id');
            $table->string('thumbnail');
            $table->string('medium');
            $table->string('full');
        });

        Schema::create('photo_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_id');
            $table->text('comments');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('filename', 25)->default('noimage.gif');
            $table->integer('external_photo_id')->nullable();
            $table->text('caption')->nullable();
            $table->foreignId('photo_album_id');
            $table->smallInteger('views')->default(0);
            $table->smallInteger('votes')->default(0);
            $table->float('rating', 10, 0)->default(0);
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('photo_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_id');
            $table->foreignId('user_id');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id');
            $table->foreignId('user_id')->nullable();
            $table->string('email', 50)->nullable();
            $table->boolean('attending')->nullable();
            $table->char('code', 13)->nullable();
            $table->text('response')->nullable();
            $table->timestamps();
        });

        Schema::create('navigation_links', function (Blueprint $table) {
            $table->id();
            $table->string('link');
            $table->string('route_name')->nullable();
            $table->tinyInteger('group');
            $table->tinyInteger('order');
        });

        Schema::create('news_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id');
            $table->text('comments');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->text('news');
            $table->string('external_type', 20)->nullable();
            $table->string('external_id')->nullable();
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('notification', 50)->nullable();
            $table->string('data', 50);
            $table->boolean('read')->default(false);
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('poll_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id');
            $table->text('comments');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id');
            $table->text('option');
            $table->integer('votes')->default(0);
        });

        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_id');
            $table->foreignId('poll_id');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        $poll = new Poll();

        $poll->question        = _gettext('What do you think of Family Connections?');
        $poll->created_user_id = 1;
        $poll->updated_user_id = 1;

        $poll->save();

        $pollOptionData = [
            _gettext('Easy to use!'),
            _gettext('Visually appealing!'),
            _gettext('Just what our family needed!'),
        ];

        foreach ($pollOptionData as $i => $option)
        {
            $pollOption = new PollOption();
 
            $pollOption->poll_id = 1;
            $pollOption->option  = $option;

            $pollOption->save();
        }

        Schema::create('prayers', function (Blueprint $table) {
            $table->id();
            $table->string('for', 50);
            $table->text('desc');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('private_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('title', 50);
            $table->text('msg');
            $table->boolean('read')->default(false);
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('recipe_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id');
            $table->text('comments');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('recipe_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description')->nullable();
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('thumbnail')->default('no_recipe.jpg');
            $table->foreignId('recipe_category_id');
            $table->text('ingredients');
            $table->text('directions');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->text('status');
            $table->foreignId('parent_id')->default(0);
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('tree_individuals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('family_id')->nullable();
            $table->string('given_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('maiden')->nullable();
            $table->string('alias')->nullable();
            $table->string('nickname')->nullable();
            $table->string('name_prefix')->nullable();                  // Mr. Mrs. Dr. etc.
            $table->string('name_suffix')->nullable();                  // Jr. Sr. I II BA. MD. etc.
            $table->boolean('living')->default(true);
            $table->char('dob_year', 4)->nullable();
            $table->char('dob_month', 2)->nullable();
            $table->char('dob_day', 2)->nullable();
            $table->char('dod_year', 4)->nullable();
            $table->char('dod_month', 2)->nullable();
            $table->char('dod_day', 2)->nullable();
            $table->enum('sex', ['U', 'O', 'M', 'F'])->default('U');    // Unknown, Other, Male, Female
            $table->string('description')->nullable();
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('tree_families', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('tree_events', function (Blueprint $table) {
            $table->id();
            $table->string('type', 4);                  // BIRT, MARR, BAPM, DEAT, etc.
            $table->string('description')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('places_id')->nullable();
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('tree_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('individual_id');
            $table->foreignId('family_id');
            $table->string('relationship', 4);
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('tree_places', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('tree_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('filename');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('user_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('award', 100);
            $table->integer('month');
            $table->integer('item_id')->nullable();
            $table->smallInteger('count')->default(0);
            $table->timestamps();
        });

        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('language', 6)->default('en_US');
            $table->string('timezone')->default('America/New_York');
        });

        Schema::create('video_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id');
            $table->text('comments');
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('external_videos', function (Blueprint $table) {
            $table->id();
            $table->string('source_id');
            $table->integer('duration')->nullable();
            $table->string('source', 50)->nullable();
            $table->integer('height')->default(420);
            $table->integer('width')->default(780);
            $table->boolean('active')->default(true);
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('external_video_id')->nullable();
            $table->foreignId('created_user_id');
            $table->foreignId('updated_user_id');
            $table->timestamps();
        });

        \DB::statement("
            CREATE OR REPLACE VIEW view_whats_new_updates
            AS
            (SELECT
                'DISCUSSION' AS type,   -- object type
                dc.id,                  -- object id
                dc.created_at,
                dc.updated_at,
                dc.updated_user_id,
                u.name,
                u.displayname,
                u.avatar,
                u.email,
                d.title,                -- object title
                dc.comments             -- object comments
            FROM
                discussion_comments AS dc
                LEFT JOIN discussions AS d ON dc.discussion_id = dc.id
                LEFT JOIN users AS u ON dc.updated_user_id = u.id)
            UNION
            (SELECT
                'ADDRESS_ADD' AS type, a.id, a.created_at, a.updated_at, a.updated_user_id, u.name, u.displayname, u.avatar, u.email, 'n/a' AS title, 'n/a' as comments
            FROM
                addresses AS a, users AS u
            WHERE
                a.updated_user_id = u.id)
            UNION
            (SELECT
                'NEW_USER' AS type, u.id, u.created_at, u.updated_at, u.id, u.name, u.displayname, u.avatar, u.email, 'n/a', 'n/a'
            FROM
                users AS u
            WHERE
                activated > 0)
            UNION
            (SELECT
                'PHOTOS' AS type, p.filename, p.created_at, p.updated_at, p.updated_user_id, u.name, u.displayname, u.avatar, u.email, a.name, a.description
            FROM
                photos AS p
                LEFT JOIN photo_albums AS a ON p.photo_album_id = a.id
                LEFT JOIN users AS u ON p.updated_user_id = u.id)
            UNION
            (SELECT
                'EVENT' AS type, e.id, e.created_at, e.updated_at, e.updated_user_id, u.name, u.displayname, u.avatar, u.email, e.title, e.date
            FROM
                events AS e
                LEFT JOIN users as u ON e.updated_user_id = u.id)
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('alerts');
        Schema::dropIfExists('user_changes');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('discussions');
        Schema::dropIfExists('discussion_comments');
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_categories');
        Schema::dropIfExists('photo_album_comments');
        Schema::dropIfExists('photo_albums');
        Schema::dropIfExists('external_photos');
        Schema::dropIfExists('photo_comments');
        Schema::dropIfExists('photos');
        Schema::dropIfExists('photo_users');
        Schema::dropIfExists('invitations');
        Schema::dropIfExists('navigation_links');
        Schema::dropIfExists('news_comments');
        Schema::dropIfExists('news');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('poll_comments');
        Schema::dropIfExists('poll_options');
        Schema::dropIfExists('poll_votes');
        Schema::dropIfExists('polls');
        Schema::dropIfExists('prayers');
        Schema::dropIfExists('private_messages');
        Schema::dropIfExists('recipe_comments');
        Schema::dropIfExists('recipe_categories');
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('relationships');
        Schema::dropIfExists('statuses');
        Schema::dropIfExists('tree_individuals');
        Schema::dropIfExists('tree_families');
        Schema::dropIfExists('tree_events');
        Schema::dropIfExists('tree_relationships');
        Schema::dropIfExists('tree_places');
        Schema::dropIfExists('tree_media');
        Schema::dropIfExists('user_awards');
        Schema::dropIfExists('user_settings');
        Schema::dropIfExists('users');
        Schema::dropIfExists('video_comments');
        Schema::dropIfExists('external_videos');
        Schema::dropIfExists('videos');
    }
}
