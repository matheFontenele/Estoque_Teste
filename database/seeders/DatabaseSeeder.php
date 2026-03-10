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
            'nome' => 'Alucom Base',
            'localizacao' => 'Fortaleza - CE'
        ]);

        $estoqueSaoPaulo = Estoque::create([
            'nome' => 'Filial Santa Catarina',
            'localizacao' => 'Florianopolis - SC'
        ]);

        // 3. Criar Clientes (Conforme a nova estrutura de CNPJ)
        $clientes = [
            [
                'nome' => 'HUAC - Hospital Universitario Alcides Carneiro',
                'cnpj' => '12.345.678/0001-00',
                'estado' => 'PB',
                'cidade' => 'Campina Grande',
                'endereco' => 'Endereço de teste tal CEP 0000000',
                'sla' => [
                    'Atendimento' => 4,
                    'Insumos' => 24,
                    'Substituição' => 48,
                    'Remanejamento' => 72,
                    'Tipo' => 'Compativel'

                ]
            ],
            [
                'nome' => 'Comando da Aeronautica',
                'cnpj' => '98.765.432/0001-99',
                'estado' => 'DF',
                'cidade' => 'Brasilia',
                'endereco' => 'Endereço de teste tal CEP 0000000',
                'sla' => [
                    'Atendimento' => 4,    // Tempo em horas, por exemplo
                    'Insumos' => 24,
                    'Substituição' => 48,
                    'Remanejamento' => 72,
                    'Tipo' => 'Original'
                ]
            ],
        ];

        foreach ($clientes as $c) {
            Cliente::create($c);
        }

        // 4. Criar Equipamentos vinculados aos Estoques específicos
        $equipamentos = [
            [
                'nome' => 'MICRO',
                'tombo' => 86745,
                'serial' => 'WDAS56455',
                'quantidade_estoque' => 15,
                'situacao' => 'disponivel',
                'cor' => 'N/A',
                'descricao' => 'I7, 8GB RAM, SSD 240GB',
                'estoque_id' => $estoqueFortaleza->id
            ],
            [
                'nome' => 'MULTIFUNCIONAL',
                'tombo' => 88769,
                'serial' => 'WDAS56485',
                'quantidade_estoque' => 3,
                'situacao' => 'disponivel',
                'cor' => 'N/A',
                'descricao' => 'MONO KYOCERA ECOSYS MA5500IFX',
                'estoque_id' => $estoqueFortaleza->id
            ],
            [
                'nome' => 'Roteador Mikrotik RB4011',
                'tombo' => 72548,
                'serial' => 'WDAS56445',
                'quantidade_estoque' => 8,
                'situacao' => 'disponivel',
                'cor' => 'N/A',
                'descricao' => 'Roteador de borda',
                'estoque_id' => $estoqueSaoPaulo->id
            ],
            [
                'nome' => 'Monitor Samsung 24"',
                'tombo' => 32545,
                'serial' => 'WDAS55855',
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
