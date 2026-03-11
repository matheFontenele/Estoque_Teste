<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\Estoque;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar Usuário Admin (Usando updateOrCreate para evitar erros)
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Matheus Fontenele',
                'password' => Hash::make('12345678'),
            ]
        );

        // 2. Criar Estoques
        $estoqueFortaleza = Estoque::updateOrCreate(['nome' => 'Alucom Base'], ['localizacao' => 'Fortaleza - CE']);
        $estoqueSantaCatarina = Estoque::updateOrCreate(['nome' => 'Filial Santa Catarina'], ['localizacao' => 'Florianopolis - SC']);
        $estoqueParaiba = Estoque::updateOrCreate(['nome' => 'Filial Paraiba'], ['localizacao' => 'João Pessoa - PB']);

        // 3. Criar Clientes (CNPJs únicos corrigidos)
        $clientes = [
            [
                'nome' => 'HUAC - Hospital Universitario Alcides Carneiro',
                'cnpj' => '12.345.678/0001-00',
                'estado' => 'PB',
                'cidade' => 'Campina Grande',
                'endereco' => 'Endereço de teste tal CEP 0000000',
                'sla' => ['Atendimento' => 4, 'Insumos' => 24, 'Substituição' => 48, 'Remanejamento' => 72, 'Tipo' => 'Compativel']
            ],
            [
                'nome' => 'Comando da Aeronautica',
                'cnpj' => '98.765.432/0001-99', // CNPJ 1
                'estado' => 'DF',
                'cidade' => 'Brasilia',
                'endereco' => 'Endereço de teste tal CEP 0000000',
                'sla' => ['Atendimento' => 4, 'Insumos' => 24, 'Substituição' => 48, 'Remanejamento' => 72, 'Tipo' => 'Compativel']
            ],
            [
                'nome' => 'Receita Federal',
                'cnpj' => '11.222.333/0001-44', // ALTERADO PARA SER ÚNICO
                'estado' => 'SC',
                'cidade' => 'Florianopolis',
                'endereco' => 'Endereço de teste tal CEP 0000000',
                'sla' => ['Atendimento' => 4, 'Insumos' => 24, 'Substituição' => 48, 'Remanejamento' => 72, 'Tipo' => 'Original']
            ],
        ];

        foreach ($clientes as $c) {
            Cliente::updateOrCreate(['cnpj' => $c['cnpj']], $c);
        }

        // 4. Criar Equipamentos vinculados aos Estoques específicos
        $equipamentos = [
            [
                'nome' => 'MICRO',
                'tombo' => '86745',
                'serial' => 'WDAS56455',
                'quantidade_estoque' => 1,
                'situacao' => 'disponivel',
                'cor' => 'N/A',
                'descricao' => 'I7, 8GB RAM, SSD 240GB',
                'estoque_id' => $estoqueFortaleza->id
            ],
            [
                'nome' => 'MULTIFUNCIONAL',
                'tombo' => '88769',
                'serial' => 'WDAS56485',
                'quantidade_estoque' => 1,
                'situacao' => 'disponivel',
                'cor' => 'N/A',
                'descricao' => 'MONO KYOCERA ECOSYS MA5500IFX',
                'estoque_id' => $estoqueFortaleza->id
            ],
            [
                'nome' => 'Toner TK 5372',
                'tombo' => null, // Sem tombo
                'serial' => null,
                'quantidade_estoque' => 15,
                'situacao' => 'disponivel',
                'cor' => 'Preto',
                'descricao' => 'Compativel com impressora MA5500',
                'estoque_id' => $estoqueSantaCatarina->id
            ],
        ];

        foreach ($equipamentos as $e) {
            Equipamento::updateOrCreate(
                ['serial' => $e['serial'], 'tombo' => $e['tombo']],
                $e
            );
        }
    }
}
