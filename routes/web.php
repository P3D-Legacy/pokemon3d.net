<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ServerController;
use AliBayat\LaravelCategorizable\Category;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\Skin\SkinController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Auth\TwitchController;
use App\Http\Controllers\Skin\ImportController;
use App\Http\Controllers\Auth\DiscordController;
use App\Http\Controllers\Auth\TwitterController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Skin\SkinHomeController;
use App\Http\Controllers\Skin\PlayerSkinController;
use App\Http\Controllers\Skin\UploadedSkinController;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/redirect/wiki", function () {
    return redirect("https://pokemon3d.net/wiki/");
})->name("wiki");

<<<<<<< HEAD
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('server', ServerController::class);
    Route::resource('resource', ResourceController::class);
    Route::get('/resource/category/{name}', function ($name) {
        $resources = Category::findByName($name)->entries(\App\Models\Resource::class)->paginate(10);
        return view("resources.index", [
            "categories" => Category::all(),
            "resources" => $resources
        ]);
    })->name('resource.category');
    
    Route::get('/member/{user}', [MemberController::class, 'show'])->name('member.show');
    
    Route::prefix('skin')->group(function () {
        Route::get('/', [SkinHomeController::class, 'index'])->name('skin-home');
        Route::get('/my', function() {
            return redirect()->route('skin-home');
        })->name('skins-my');
    
        Route::get('/import/{id}', [ImportController::class, 'import'])->name('import');
        
        Route::get('/player', [PlayerSkinController::class, 'index'])->name('player-skins');
        Route::post('/player/create', [PlayerSkinController::class, 'store'])->name('player-skin-store');
        Route::get('/player/duplicate', [PlayerSkinController::class, 'duplicate'])->name('player-skin-duplicate');
        Route::post('/player/delete/{id}', [PlayerSkinController::class, 'destroyAsAdmin'])->name('player-skin-destroy-admin');
        Route::get('/player/delete', [PlayerSkinController::class, 'destroy'])->name('player-skin-destroy');
        
        Route::get('/public', function(){ return redirect()->route('skins-newest');})->name('skins');
        Route::get('/public/new', [SkinController::class, 'newestpublicskins'])->name('skins-newest');
        Route::get('/public/popular', [SkinController::class, 'popularpublicskins'])->name('skins-popular');
        Route::get('/public/{uuid}', [SkinController::class, 'show'])->name('skin-show');
        Route::get('/create', [SkinController::class, 'create'])->name('skin-create');
        Route::post('/create', [SkinController::class, 'store'])->name('skin-store');
        Route::get('/{uuid}/edit', [SkinController::class, 'edit'])->name('skin-edit');
        Route::post('/{uuid}/edit', [SkinController::class, 'update'])->name('skin-update');
        Route::get('/{uuid}/delete', [SkinController::class, 'destroy'])->name('skin-destroy');
        Route::get('/{uuid}/apply', [SkinController::class, 'apply'])->name('skin-apply');
        Route::get('/{uuid}/like', [SkinController::class, 'like'])->name('skin-like');
        
        Route::get('/uploaded', [UploadedSkinController::class, 'index'])->name('uploaded-skins');
        Route::post('/uploaded/delete/{id}', [UploadedSkinController::class, 'destroy'])->name('uploaded-skin-destroy');
    });

    Route::prefix('admin')->middleware(['role:super-admin|admin'])->group(function () {
        Route::get('health', HealthCheckResultsController::class);
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('posts', PostController::class);
        Route::resource('tags', TagController::class);
        Route::view('categories', 'category.index')->name('categories.index');
    });
    
=======
Route::get("/redirect/forum", function () {
    return redirect("https://pokemon3d.net/forum/");
})->name("forum");

Route::get("/redirect/github", function () {
    return redirect("https://github.com/P3D-Legacy");
})->name("github");

Route::get("/redirect/discord", function () {
    return redirect(config("discord.invite_url"));
})->name("discord");

Route::get("/", [HomeController::class, "index"])->name("home");
Route::resource("blog", BlogController::class);

Route::group(["prefix" => "login"], function () {
    Route::get("/discord", [
        DiscordController::class,
        "redirectToProvider",
    ])->name("discord.login");
    Route::get("/discord/callback", [
        DiscordController::class,
        "handleProviderCallback",
    ]);
    Route::get("/twitter", [
        TwitterController::class,
        "redirectToProvider",
    ])->name("twitter.login");
    Route::get("/twitter/callback", [
        TwitterController::class,
        "handleProviderCallback",
    ]);
    Route::get("/facebook", [
        FacebookController::class,
        "redirectToProvider",
    ])->name("facebook.login");
    Route::get("/facebook/callback", [
        FacebookController::class,
        "handleProviderCallback",
    ]);
    Route::get("/twitch", [
        TwitchController::class,
        "redirectToProvider",
    ])->name("twitch.login");
    Route::get("/twitch/callback", [
        TwitchController::class,
        "handleProviderCallback",
    ]);
>>>>>>> develop
});

Route::group(["middleware" => ["auth:sanctum", "verified"]], function () {
    Route::get("/dashboard", function () {
        return view("dashboard");
    })->name("dashboard");

    Route::resource("server", ServerController::class);

    Route::get("/member/{user}", [MemberController::class, "show"])->name(
        "member.show"
    );

    Route::prefix("skin")->group(function () {
        Route::get("/", [SkinHomeController::class, "index"])->name(
            "skin-home"
        );
        Route::get("/my", function () {
            return redirect()->route("skin-home");
        })->name("skins-my");

        Route::get("/import/{id}", [ImportController::class, "import"])->name(
            "import"
        );

        Route::get("/player", [PlayerSkinController::class, "index"])->name(
            "player-skins"
        );
        Route::post("/player/create", [
            PlayerSkinController::class,
            "store",
        ])->name("player-skin-store");
        Route::get("/player/duplicate", [
            PlayerSkinController::class,
            "duplicate",
        ])->name("player-skin-duplicate");
        Route::post("/player/delete/{id}", [
            PlayerSkinController::class,
            "destroyAsAdmin",
        ])->name("player-skin-destroy-admin");
        Route::get("/player/delete", [
            PlayerSkinController::class,
            "destroy",
        ])->name("player-skin-destroy");

        Route::get("/public", function () {
            return redirect()->route("skins-newest");
        })->name("skins");
        Route::get("/public/new", [
            SkinController::class,
            "newestpublicskins",
        ])->name("skins-newest");
        Route::get("/public/popular", [
            SkinController::class,
            "popularpublicskins",
        ])->name("skins-popular");
        Route::get("/public/{uuid}", [SkinController::class, "show"])->name(
            "skin-show"
        );
        Route::get("/create", [SkinController::class, "create"])->name(
            "skin-create"
        );
        Route::post("/create", [SkinController::class, "store"])->name(
            "skin-store"
        );
        Route::get("/{uuid}/edit", [SkinController::class, "edit"])->name(
            "skin-edit"
        );
        Route::post("/{uuid}/edit", [SkinController::class, "update"])->name(
            "skin-update"
        );
        Route::get("/{uuid}/delete", [SkinController::class, "destroy"])->name(
            "skin-destroy"
        );
        Route::get("/{uuid}/apply", [SkinController::class, "apply"])->name(
            "skin-apply"
        );
        Route::get("/{uuid}/like", [SkinController::class, "like"])->name(
            "skin-like"
        );

        Route::get("/uploaded", [UploadedSkinController::class, "index"])->name(
            "uploaded-skins"
        );
        Route::post("/uploaded/delete/{id}", [
            UploadedSkinController::class,
            "destroy",
        ])->name("uploaded-skin-destroy");
    });

    Route::prefix("admin")
        ->middleware(["role:super-admin|admin"])
        ->group(function () {
            Route::get("health", HealthCheckResultsController::class);
            Route::resource("users", UserController::class);
            Route::resource("roles", RoleController::class);
            Route::resource("permissions", PermissionController::class);
            Route::resource("posts", PostController::class);
            Route::resource("tags", TagController::class);
        });
});
