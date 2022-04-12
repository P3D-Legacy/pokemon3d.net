<?php

namespace App\Spotlight;

use App\Models\Skin;
use App\Models\User;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class SkinSearch extends SpotlightCommand
{
    /**
     * This is the name of the command that will be shown in the Spotlight component.
     */
    protected string $name = 'Search skin';

    /**
     * This is the description of your command which will be shown besides the command name.
     */
    protected string $description = 'Search for a skin by name.';

    /**
     * You can define any number of additional search terms (also known as synonyms)
     * to be used when searching for this command.
     */
    protected array $synonyms = ['skin', 'skins', 'skin search'];

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()->add(
            // In this example we will register a 'team' dependency
            SpotlightCommandDependency::make('skin')
                // The default Spotlight placeholder will be changed to your dependency place holder
                ->setPlaceholder('For which skin do you want to search?')
        );
    }

    /**
     * Spotlight will resolve dependencies by calling the search method followed by your dependency name.
     * The method will receive the search query as the parameter.
     */
    public function searchSkin($query)
    {
        return Skin::where('name', 'like', "%$query%")
            ->get()
            ->map(function (Skin $skin) {
                // You must map your search result into SpotlightSearchResult objects
                return new SpotlightSearchResult($skin->uuid, $skin->name, sprintf('Show details for %s', $skin->name));
            });
    }

    /**
     * When all dependencies have been resolved the execute method is called.
     * You can type-hint all resolved dependency you defined earlier.
     */
    public function execute(Spotlight $spotlight, Skin $skin)
    {
        $spotlight->redirectRoute('skin-show', $skin);
    }

    /**
     * You can provide any custom logic you want to determine whether the
     * command will be shown in the Spotlight component. If you don't have any
     * logic you can remove this method. You can type-hint any dependencies you
     * need and they will be resolved from the container.
     */
    public function shouldBeShown(): bool
    {
        return true;
    }
}
