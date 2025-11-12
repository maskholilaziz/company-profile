<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use RalphJSmit\Filament\SEO\SEO as SEOComponent;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')->tabs([
                    Tab::make('Content')->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(debounce: '500ms') // Auto-update slug
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Page::class, 'slug', ignoreRecord: true), // Pastikan slug unik
                        RichEditor::make('content'),
                        Toggle::make('is_published')
                            ->label('Publish Page?'),
                    ]),
                    Tab::make('Featured Image')
                        ->schema([
                            // Ini adalah File Upload dari Spatie Media Library
                            SpatieMediaLibraryFileUpload::make('featured_image')
                                ->collection('featured_image') // Sesuai nama di Model
                                ->image()
                                ->responsiveImages() // Gunakan responsive images
                                ->label('Upload Featured Image'),
                        ]),
                    Tab::make('SEO')
                        ->schema([
                            // Ini adalah komponen SEO
                            SEOComponent::make(),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}
