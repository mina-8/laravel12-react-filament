<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use App\Models\HomePage as HomePageModel;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Notifications\Notification;

use Pixelpeter\FilamentLanguageTabs\Forms\Components\LanguageTabs;
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

class HomePage extends Page implements HasForms
{
    use InteractsWithForms;
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.home-page';

    // public static function getNavigationGroup(): string
    // {
    //     return __('filament-panels::layout.webist.settingsite');
    // }
    public function getHeading(): string
    {
        return __('filament-panels::resources/pages/homepage.title');
    }
    public function getTitle(): string
    {
        return __('filament-panels::resources/pages/homepage.title');
    }
    public static function getNavigationLabel(): string
    {
        return __('filament-panels::resources/pages/homepage.title');
    }

    public $locale;
    public $hero_content;
    public function mount(): void
    {
        $this->form->fill([
            'hero_content' => json_decode(HomePageModel::getValue('hero_content', '{}'), true),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('tabs')
                ->tabs([
                    Tab::make('hero_section')
                        ->label(__('filament-panels::resources/pages/homepage.tabs.hero_section'))
                        ->schema([
                            LanguageTabs::make([
                                QuillEditor::make('hero_content')
                                    ->label(__('filament-panels::resources/pages/homepage.fields.hero_content'))
                                // ->language($locale)
                                // ->columnSpanFull(),
                            ]),

                        ]),
                    Tab::make('how_work')
                    ->label(__('filament-panels::resources/pages/homepage.tabs.how_work'))
                    ->schema([
                        // ...
                    ])
                ])
        ];
    }

    public function save() : void
    {
        $data = $this->form->getState();
        foreach( $data as $key => $value){
            if(in_array($key , ['hero_content']) && is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
            HomePageModel::setValue($key, $value);
        }

        Notification::make()
            ->title(__('filament-panels::resources/pages/homepage.messages.saved.title'))
            ->success()
            ->send();
    }
}
