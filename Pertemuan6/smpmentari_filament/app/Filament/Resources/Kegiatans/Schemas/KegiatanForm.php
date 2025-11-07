<?php

namespace App\Filament\Resources\Kegiatans\Schemas;

use Filament\Schemas\Schema; // <-- pakai Schema builder
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

class KegiatanForm
{
    public static function build(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('judul')
                ->label('Judul')
                ->required()
                ->maxLength(255),

            DatePicker::make('tanggal')
                ->label('Tanggal')
                ->native(false)
                ->displayFormat('d M Y')
                ->required(),

            TextInput::make('ringkasan')
                ->label('Ringkasan')
                ->maxLength(255),

            Textarea::make('isi')
                ->label('Isi')
                ->rows(6),

            FileUpload::make('foto')
                ->label('Foto')
                ->image()
                ->disk('public')
                ->directory('kegiatan')
                ->visibility('public')
                ->downloadable()
                ->imageEditor(),
        ])->columns(2);
    }
}
