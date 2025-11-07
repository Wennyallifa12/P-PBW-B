<?php

namespace App\Filament\Resources\Siswas\Schemas;

use Filament\Schemas\Schema; // <-- pakai Schema
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;

class SiswaForm
{
    public static function build(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('nisn')
                ->label('NISN')
                ->required()
                ->maxLength(20)
                ->unique(ignoreRecord: true),

            TextInput::make('nama')
                ->label('Nama')
                ->required()
                ->maxLength(255),

            Select::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                ->options([
                    'L' => 'Laki-Laki',
                    'P' => 'Perempuan',
                ])
                ->required(),

            TextInput::make('kelas')
                ->label('Kelas')
                ->placeholder('Mis: 7A, 8B, 9C')
                ->required()
                ->maxLength(10),

            DatePicker::make('tanggal_lahir')
                ->label('Tanggal Lahir')
                ->native(false)
                ->displayFormat('d M Y'),

            Textarea::make('alamat')
                ->label('Alamat')
                ->rows(3),
        ])->columns(2);
    }
}
