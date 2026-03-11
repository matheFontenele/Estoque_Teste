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
        // 1. Criar Usuário Admin
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Matheus Fontenele',
                'password' => Hash::make('12345678'),
            ]
        );

        // 2. Criar Estoques (Locais)
        $estoqueFortaleza = Estoque::updateOrCreate(['nome' => 'Alucom Base'], ['localizacao' => 'Fortaleza - CE']);
        $estoqueSantaCatarina = Estoque::updateOrCreate(['nome' => 'Filial Santa Catarina'], ['localizacao' => 'Florianopolis - SC']);
        $estoqueParaiba = Estoque::updateOrCreate(['nome' => 'Filial Paraiba'], ['localizacao' => 'João Pessoa - PB']);

        // 3. Criar Clientes
        $clientes = [
            [
                'nome' => 'HUAC - Hospital Universitario Alcides Carneiro',
                'cnpj' => '12.345.678/0001-00',
                'estado' => 'PB',
                'cidade' => 'Campina Grande',
                'contrato' => 'Alucom',
                'endereco' => 'Endereço de teste tal CEP 0000000',
                'sla' => ['Atendimento' => 4, 'Insumos' => 24, 'Substituição' => 48, 'Remanejamento' => 72, 'Tipo' => 'Compativel']
            ],
            [
                'nome' => 'Receita Federal',
                'cnpj' => '11.222.333/0001-44',
                'estado' => 'SC',
                'cidade' => 'Florianopolis',
                'contrato' => 'Moreia',
                'endereco' => 'Endereço de teste tal CEP 0000000',
                'sla' => ['Atendimento' => 4, 'Insumos' => 24, 'Substituição' => 48, 'Remanejamento' => 72, 'Tipo' => 'Original']
            ],
        ];

        foreach ($clientes as $c) {
            Cliente::updateOrCreate(['cnpj' => $c['cnpj']], $c);
        }

        // 4. Criar Itens (Equipamentos e Insumos) com a nova hierarquia
        $itensInventario = [
            // Categoria: EQUIPAMENTOS
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Computadores',
                'nome' => 'Desktop Dell Optiplex',
                'tombo' => '86745',
                'serial' => 'WDAS56455',
                'quantidade_estoque' => 1,
                'condicao' => 'Alugado',
                'descricao' => 'I7, 8GB RAM, SSD 240GB',
                'cor' => 'Não se Aplica',
                'compativel_com' => 'Não se Aplica',
                'estoque_id' => $estoqueFortaleza->id,
                'cliente_id' => null,
            ],
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Impressoras/Multifuncionais',
                'nome' => 'Kyocera Ecosys MA5500ifx',
                'tombo' => '88769',
                'serial' => 'WDAS56485',
                'quantidade_estoque' => 1,
                'condicao' => 'Disponivel',
                'descricao' => 'Multifuncional Mono de Alta Performance',
                'cor' => 'Não se Aplica',
                'compativel_com' => 'Não se Aplica',
                'estoque_id' => null,
                'cliente_id' => 1,
            ],
            // Categoria: INSUMOS
            [
                'categoria' => 'Insumos',
                'subcategoria' => 'Toners/Tintas',
                'nome' => 'Toner TK 5372',
                'tombo' => null,
                'serial' => 'SN-TK5372-001', // Identificador único para o seeder não duplicar
                'quantidade_estoque' => 15,
                'condicao' => null,
                'descricao' => 'Toner de alto rendimento',
                'cor' => 'Preto',
                'compativel_com' => 'Kyocera MA5500',
                'estoque_id' => $estoqueSantaCatarina->id
            ],
            [
                'categoria' => 'Insumos',
                'subcategoria' => 'Peças',
                'nome' => 'Cilindro DR-3440',
                'tombo' => null,
                'serial' => 'SN-DR3440-PECA',
                'quantidade_estoque' => 5,
                'condicao' => null,
                'descricao' => 'Unidade de imagem',
                'cor' => 'Não se Aplica',
                'compativel_com' => 'Brother L6900dw',
                'estoque_id' => $estoqueParaiba->id
            ],
        ];

        foreach ($itensInventario as $item) {
            Equipamento::updateOrCreate(
                ['serial' => $item['serial']], 
                $item
            );
        }
    }
}