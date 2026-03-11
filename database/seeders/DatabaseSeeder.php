<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\Estoque;
use App\Models\GuiaAdi;
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
        $estoqueSul = Estoque::updateOrCreate(['nome' => 'Filial Sul'], ['localizacao' => 'Florianopolis - SC']);
        $estoqueNordeste = Estoque::updateOrCreate(['nome' => 'Filial Nordeste'], ['localizacao' => 'João Pessoa - PB']);

        // 3. Clientes
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
        $prefeitura = Cliente::where('nome', 'like', '%Prefeitura%')->first();

        // 4. Guia ADI (Catálogo Técnico de Impressoras)
        $modelosGuia = [
            [
                'fabricante' => 'Kyocera',
                'marca_modelo' => 'Ecosys MA5500ifx',
                'familia' => 'Multifuncional Laser Mono A4',
                'toner' => 'TK-3412',
                'rendimento' => '25.000 páginas',
                'ppm' => 55,
                'papel' => 'A4, Carta, Ofício',
                'voltagem' => '120V',
                'funcoes' => ['Impressão', 'Cópia', 'Digitalização', 'Fax'],
                'resolucao' => '1200 x 1200 dpi',
                'memoria' => '1.5 GB (Max 3.5 GB)',
                'hdd' => 'Não Acompanha (Opcional SSD)',
                'duplex' => 'Sim',
                'capacidade_papel' => '600 / 2.600 folhas',
                'pecas' => 'Kit de Manutenção MK-3372',
                'cartao_sd' => 'Aceita',
                'ndd' => 'ACEITA SOLUÇÃO EMBARCADA',
                'obs' => 'Equipamento de alta performance para grandes grupos de trabalho.'
            ],
            [
                'fabricante' => 'Brother',
                'marca_modelo' => 'MFC-L6902DW',
                'familia' => 'Multifuncional Laser Mono Profissional',
                'toner' => 'TN-3492',
                'rendimento' => '20.000 páginas',
                'ppm' => 52,
                'papel' => 'A4, A5, A6',
                'voltagem' => '110V',
                'funcoes' => ['Impressão', 'Cópia', 'Digitalização'],
                'resolucao' => '1200 x 1200 dpi',
                'memoria' => '1 GB',
                'hdd' => 'Não Acompanha',
                'duplex' => 'Sim',
                'capacidade_papel' => '520 / 1.610 folhas',
                'pecas' => 'Unidade de Cilindro DR-3440',
                'cartao_sd' => 'Não Aceita',
                'ndd' => 'ACEITA SOLUÇÃO EMBARCADA',
                'obs' => 'Ideal para outsourcing de médio porte.'
            ]
        ];

        foreach ($modelosGuia as $guia) {
            GuiaAdi::updateOrCreate(['marca_modelo' => $guia['marca_modelo']], $guia);
        }

        // 5. Inventário (Equipamentos em Clientes e Estoques)
        $itensInventario = [
            // OPERACIONAL (VERDE)
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Impressoras/Multifuncionais',
                'nome' => 'Kyocera Ecosys MA5500ifx',
                'serial' => 'KY001-OPS',
                'tombo' => '10001',
                'condicao' => 'Operacional',
                'cliente_id' => $huac->id,
                'estoque_id' => null,
            ],
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Scanners',
                'nome' => 'Kodak Alaris S2050',
                'serial' => 'KD001-OPS',
                'tombo' => '10002',
                'condicao' => 'Operacional',
                'cliente_id' => $tjce->id,
                'estoque_id' => null,
            ],
            // RESERVA (AZUL)
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Computadores',
                'nome' => 'HP EliteDesk 800 G6',
                'serial' => 'HP001-RES',
                'tombo' => '10003',
                'condicao' => 'Reserva',
                'cliente_id' => $huac->id,
                'estoque_id' => null,
            ],
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Impressoras/Multifuncionais',
                'nome' => 'Brother MFC-L6902DW',
                'serial' => 'BR001-RES',
                'tombo' => '10004',
                'condicao' => 'Reserva',
                'cliente_id' => $prefeitura->id,
                'estoque_id' => null,
            ],
            // DEVOLUÇÃO (AMARELO)
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Impressoras/Multifuncionais',
                'nome' => 'Epson L3250',
                'serial' => 'EP001-DEV',
                'tombo' => '10005',
                'condicao' => 'Devolução',
                'cliente_id' => $tjce->id,
                'estoque_id' => null,
                'descricao' => 'Cabeça de impressão entupida'
            ],

            // DISPONÍVEL NO ESTOQUE (VERDE)
            [
                'categoria' => 'Equipamentos',
                'subcategoria' => 'Computadores',
                'nome' => 'Dell Optiplex 7090',
                'serial' => 'DELL-EST-01',
                'tombo' => '10006',
                'condicao' => 'Operacional',
                'cliente_id' => null,
                'estoque_id' => $estoqueFortaleza->id,
            ],

            // INSUMOS NOS ESTOQUES
            [
                'categoria' => 'Insumos',
                'subcategoria' => 'Toners/Tintas',
                'nome' => 'Toner TK-3412',
                'serial' => 'SN-TK3412-FOT',
                'quantidade_estoque' => 30,
                'estoque_id' => $estoqueFortaleza->id,
                'compativel_com' => 'Kyocera MA5500',
            ],
            [
                'categoria' => 'Insumos',
                'subcategoria' => 'Peças',
                'nome' => 'Cilindro DR-3440',
                'serial' => 'SN-DR3440-SUL',
                'quantidade_estoque' => 10,
                'estoque_id' => $estoqueSul->id,
                'compativel_com' => 'Brother L6902',
            ]
        ];

        foreach ($itensInventario as $item) {
            Equipamento::updateOrCreate(['serial' => $item['serial']], $item);
        }
    }
}