<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\Estoque; // Certifique-se de ter o Model Estoque criado
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar Usuário Admin
        User::create([
            'name' => 'Matheus Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
        ]);

        // 2. Criar Diferentes Estoques (Unidades)
        $estoqueFortaleza = Estoque::create([
            'nome' => 'Almoxarifado Central',
            'localizacao' => 'Fortaleza - CE'
        ]);

        $estoqueSaoPaulo = Estoque::create([
            'nome' => 'Unidade Logística Sudeste',
            'localizacao' => 'São Paulo - SP'
        ]);

        // 3. Criar Clientes (Conforme a nova estrutura de CNPJ)
        $clientes = [
            [
                'nome' => 'Supermercado Alvorada',
                'cnpj' => '12.345.678/0001-00',
                'estado' => 'CE',
                'cidade' => 'Fortaleza'
            ],
            [
                'nome' => 'Posto São João',
                'cnpj' => '98.765.432/0001-99',
                'estado' => 'SP',
                'cidade' => 'São Paulo'
            ],
        ];

        foreach ($clientes as $c) {
            Cliente::create($c);
        }

        // 4. Criar Equipamentos vinculados aos Estoques específicos
        $equipamentos = [
            [
                'nome' => 'Notebook Dell Latitude',
                'tombo' => 'DELL-001',
                'quantidade_estoque' => 15,
                'situacao' => 'disponivel',
                'cor' => 'N/A',
                'descricao' => 'Processador i5, 8GB RAM',
                'estoque_id' => $estoqueFortaleza->id
            ], // <-- Verifique se tem essa vírgula e colchete
            [
                'nome' => 'Impressora HP Laserjet',
                'tombo' => 'HP-500',
                'quantidade_estoque' => 3,
                'situacao' => 'disponivel',
                'cor' => 'Preto',
                'descricao' => 'Impressora de rede',
                'estoque_id' => $estoqueFortaleza->id
            ],
            [
                'nome' => 'Roteador Mikrotik RB4011',
                'tombo' => 'MK-102',
                'quantidade_estoque' => 8,
                'situacao' => 'disponivel',
                'cor' => 'N/A',
                'descricao' => 'Roteador de borda',
                'estoque_id' => $estoqueSaoPaulo->id
            ],
            [
                'nome' => 'Monitor Samsung 24"',
                'tombo' => 'SAM-99',
                'quantidade_estoque' => 5,
                'situacao' => 'disponivel',
                'cor' => 'Preto',
                'descricao' => 'Monitor Full HD',
                'estoque_id' => $estoqueSaoPaulo->id
            ],
        ];

        foreach ($equipamentos as $e) {
            Equipamento::create($e);
        }
    }
}
