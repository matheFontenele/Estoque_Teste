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
        // 1. Usuário Admin
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Matheus Fontenele',
                'password' => Hash::make('12345678'),
            ]
        );

        // 2. Estoques (Locais de Armazenamento)
        $estoqueFortaleza = Estoque::updateOrCreate(['nome' => 'Alucom Base'], ['localizacao' => 'Fortaleza - CE']);
        $estoqueSantaCatarina = Estoque::updateOrCreate(['nome' => 'Filial Sul'], ['localizacao' => 'Florianopolis - SC']);
        $estoqueParaiba = Estoque::updateOrCreate(['nome' => 'Filial Nordeste'], ['localizacao' => 'João Pessoa - PB']);

        // 3. Clientes (Expandido)
        $clientesData = [
            [
                'nome' => 'HUAC - Hospital Universitario Alcides Carneiro',
                'cnpj' => '12.345.678/0001-00',
                'estado' => 'PB',
                'cidade' => 'Campina Grande',
                'contrato' => 'Alucom',
                'endereco' => 'R. Profa. Flora Guimarães, s/n',
                'sla' => ['Atendimento' => 4, 'Insumos' => 24, 'Substituição' => 48, 'Remanejamento' => 72, 'Tipo' => 'Compativel']
            ],
            [
                'nome' => 'Receita Federal - Porto de Itajaí',
                'cnpj' => '11.222.333/0001-44',
                'estado' => 'SC',
                'cidade' => 'Itajaí',
                'contrato' => 'Moreia',
                'endereco' => 'Av. Cel. Eugênio Müller, 622',
                'sla' => ['Atendimento' => 4, 'Insumos' => 24, 'Substituição' => 48, 'Remanejamento' => 72, 'Tipo' => 'Original']
            ],
            [
                'nome' => 'Tribunal de Justiça do Ceará',
                'cnpj' => '07.000.123/0001-99',
                'estado' => 'CE',
                'cidade' => 'Fortaleza',
                'contrato' => 'Governo CE',
                'endereco' => 'Av. Desembargador Floriano Benevides, 220',
                'sla' => ['Atendimento' => 2, 'Insumos' => 12, 'Substituição' => 24, 'Remanejamento' => 48, 'Tipo' => 'Original']
            ],
            [
                'nome' => 'Prefeitura de Campina Grande',
                'cnpj' => '08.999.888/0001-11',
                'estado' => 'PB',
                'cidade' => 'Campina Grande',
                'contrato' => 'Alucom',
                'endereco' => 'Av. Rio Branco, 404',
                'sla' => ['Atendimento' => 6, 'Insumos' => 48, 'Substituição' => 72, 'Remanejamento' => 96, 'Tipo' => 'Compativel']
            ]
        ];

        foreach ($clientesData as $c) {
            Cliente::updateOrCreate(['cnpj' => $c['cnpj']], $c);
        }

        $huac = Cliente::where('nome', 'like', '%HUAC%')->first();
        $tjce = Cliente::where('nome', 'like', '%Tribunal%')->first();
        $receita = Cliente::where('nome', 'like', '%Receita%')->first();

        // 4. Equipamentos e Insumos (Distribuição Variada)
        $itensInventario = [
            // --- EQUIPAMENTOS EM CLIENTES ---
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Impressoras/Multifuncionais',
                'nome' => 'Kyocera Ecosys MA5500ifx',
                'tombo' => '88769',
                'serial' => 'WDAS56485',
                'quantidade_estoque' => 1,
                'condicao' => 'Operacional',
                'descricao' => 'Multifuncional no setor de Triagem',
                'cor' => 'Não se Aplica',
                'compativel_com' => 'Não se Aplica',
                'estoque_id' => null,
                'cliente_id' => $huac->id,
            ],
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Computadores',
                'nome' => 'HP EliteDesk 800 G6',
                'tombo' => '90021',
                'serial' => 'BRJ021HPL',
                'quantidade_estoque' => 1,
                'condicao' => 'Reserva',
                'descricao' => 'Máquina de backup no TI local',
                'cor' => 'Não se Aplica',
                'compativel_com' => 'Não se Aplica',
                'estoque_id' => null,
                'cliente_id' => $huac->id,
            ],
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Impressoras/Multifuncionais',
                'nome' => 'Brother MFC-L6900DW',
                'tombo' => '77210',
                'serial' => 'BTH-99221-X',
                'quantidade_estoque' => 1,
                'condicao' => 'Devolução',
                'descricao' => 'Troca solicitada por falha no fusor',
                'cor' => 'Não se Aplica',
                'compativel_com' => 'Não se Aplica',
                'estoque_id' => null,
                'cliente_id' => $tjce->id,
            ],
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Scanners',
                'nome' => 'Kodak Alaris S2050',
                'tombo' => 'SCAN-001',
                'serial' => 'KDK554433',
                'quantidade_estoque' => 1,
                'condicao' => 'Operacional',
                'descricao' => 'Digitalização de processos judiciais',
                'cor' => 'Não se Aplica',
                'compativel_com' => 'Não se Aplica',
                'estoque_id' => null,
                'cliente_id' => $tjce->id,
            ],

            // --- EQUIPAMENTOS EM ESTOQUE (DISPONÍVEIS) ---
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Computadores',
                'nome' => 'Desktop Dell Optiplex',
                'tombo' => '86745',
                'serial' => 'WDAS56455',
                'quantidade_estoque' => 1,
                'condicao' => 'Operacional',
                'descricao' => 'I7, 8GB RAM, SSD 240GB',
                'cor' => 'Não se Aplica',
                'compativel_com' => 'Não se Aplica',
                'estoque_id' => $estoqueFortaleza->id,
                'cliente_id' => null,
            ],
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Impressoras/Multifuncionais',
                'nome' => 'Epson EcoTank L3250',
                'tombo' => 'EPS-990',
                'serial' => 'EPS-123456',
                'quantidade_estoque' => 1,
                'condicao' => 'Operacional',
                'descricao' => 'Nova na caixa',
                'cor' => 'Não se Aplica',
                'compativel_com' => 'Não se Aplica',
                'estoque_id' => $estoqueParaiba->id,
                'cliente_id' => null,
            ],

            // --- INSUMOS DISTRIBUÍDOS NOS ESTOQUES ---
            [
                'categoria' => 'Insumos',
                'subcategoria' => 'Toners/Tintas',
                'nome' => 'Toner TK 5372',
                'tombo' => null,
                'serial' => 'SN-TK5372-BASE',
                'quantidade_estoque' => 50,
                'condicao' => null,
                'descricao' => 'Toner para Kyocera MA5500',
                'cor' => 'Preto',
                'compativel_com' => 'Kyocera MA5500',
                'estoque_id' => $estoqueFortaleza->id,
                'cliente_id' => null,
            ],
            [
                'categoria' => 'Insumos',
                'subcategoria' => 'Toners/Tintas',
                'nome' => 'Toner TK 5372',
                'tombo' => null,
                'serial' => 'SN-TK5372-SUL',
                'quantidade_estoque' => 20,
                'condicao' => null,
                'descricao' => 'Estoque local Sul',
                'cor' => 'Preto',
                'compativel_com' => 'Kyocera MA5500',
                'estoque_id' => $estoqueSantaCatarina->id,
                'cliente_id' => null,
            ],
            [
                'categoria' => 'Insumos',
                'subcategoria' => 'Peças',
                'nome' => 'Cilindro DR-3440',
                'tombo' => null,
                'serial' => 'SN-DR3440-PECA',
                'quantidade_estoque' => 12,
                'condicao' => null,
                'descricao' => 'Unidade de imagem Brother',
                'cor' => 'Não se Aplica',
                'compativel_com' => 'Brother L6900dw',
                'estoque_id' => $estoqueParaiba->id,
                'cliente_id' => null,
            ],
            [
                'categoria' => 'Insumos',
                'subcategoria' => 'Papelaria',
                'nome' => 'Papel A4 Report 500fls',
                'tombo' => null,
                'serial' => 'PAPEL-A4-FORT',
                'quantidade_estoque' => 100,
                'condicao' => null,
                'descricao' => 'Caixas de papel sulfite',
                'cor' => 'Branco',
                'compativel_com' => 'Universal',
                'estoque_id' => $estoqueFortaleza->id,
                'cliente_id' => null,
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