<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name'),
                Forms\Components\Select::make('category_id')
                    ->label('Parent Category')
                    ->options(Category::all()->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->placeholder('Select acategory'),
                Forms\Components\TextInput::make('description'),
                Forms\Components\Fieldset::make('Image')
                    ->schema([
                        Forms\Components\ViewField::make('image_preview')
                            ->view('components.image-preview'), // Reference a Blade view for the preview
                        Forms\Components\FileUpload::make('image')
                            ->label('Category Image')
                            ->image()
                            ->maxSize(1024) // Maximum file size in KB
                            ->getUploadedFileNameForStorageUsing(null) // Prevent storing a filename
                            ->saveUploadedFileUsing(function (\Livewire\Features\SupportFileUploads\TemporaryUploadedFile $file) {
                                return base64_encode(file_get_contents($file->getRealPath()));
                            }) // Save the file content as Base64
                            ->helperText('Upload an image for the category (Max: 1MB)'),
                    ])
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->getStateUsing(fn($record) => $record->image ? 'data:image/png;base64,' . $record->image : null),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'view' => Pages\ViewProduct::route('{record}/view'),
        ];
    }
}
