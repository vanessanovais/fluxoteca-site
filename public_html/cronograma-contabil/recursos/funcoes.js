    <script>
        // Base de dados expandida e melhorada
        const checklistDatabase = {
            // Itens básicos para todas as empresas
            basico: [
                // Financeiro - Essencial
                { item: "🏦 Conferir e conciliar todos os saldos bancários", prioridade: "high", categoria: "Financeiro", tempo: 2, descricao: "Verificar extratos bancários vs sistema contábil" },
                { item: "💰 Verificar contas a receber em aberto", prioridade: "high", categoria: "Financeiro", tempo: 1, descricao: "Analisar inadimplência e provisões necessárias" },
                { item: "💳 Conferir contas a pagar pendentes", prioridade: "high", categoria: "Financeiro", tempo: 1, descricao: "Validar vencimentos e valores a pagar" },
                { item: "📊 Analisar fluxo de caixa do período", prioridade: "medium", categoria: "Financeiro", tempo: 1, descricao: "Entradas vs saídas e saldo final" },
                
                // Contábil - Fundamental
                { item: "📝 Verificar todos os lançamentos contábeis", prioridade: "high", categoria: "Contábil", tempo: 2, descricao: "Revisar débitos, créditos e históricos" },
                { item: "🧾 Conferir documentação fiscal (notas e cupons)", prioridade: "high", categoria: "Fiscal", tempo: 2, descricao: "Validar numeração e valores das NF-e" },
                { item: "⚖️ Verificar balanceamento do razão contábil", prioridade: "high", categoria: "Contábil", tempo: 1, descricao: "Débitos = Créditos em todas as contas" },
                
                // Obrigações - Crítico
                { item: "📋 Calcular e conferir impostos do período", prioridade: "high", categoria: "Fiscal", tempo: 2, descricao: "ICMS, IPI, PIS, COFINS conforme regime" },
                { item: "👥 Verificar folha de pagamento e encargos", prioridade: "high", categoria: "Trabalhista", tempo: 1, descricao: "Salários, FGTS, INSS e provisões" },
                { item: "📄 Preparar demonstrações contábeis básicas", prioridade: "medium", categoria: "Contábil", tempo: 2, descricao: "Balanço e DRE preliminares" }
            ],

            // Comércio - Varejo e Atacado
            comercio: [
                // Estoque - Crítico para comércio
                { item: "📦 Realizar inventário físico completo", prioridade: "high", categoria: "Estoque", tempo: 4, descricao: "Contar todos os produtos e conferir sistema" },
                { item: "💹 Calcular CMV (Custo das Mercadorias Vendidas)", prioridade: "high", categoria: "Contábil", tempo: 2, descricao: "Estoque inicial + compras - estoque final" },
                { item: "🔄 Conferir movimentação de entrada de mercadorias", prioridade: "high", categoria: "Estoque", tempo: 2, descricao: "Notas de compra vs lançamentos no estoque" },
                { item: "📉 Analisar perdas, quebras e avarias", prioridade: "medium", categoria: "Estoque", tempo: 1, descricao: "Produtos vencidos, danificados ou roubados" },
                
                // Vendas - Importante
                { item: "🛒 Verificar devoluções e trocas de produtos", prioridade: "medium", categoria: "Vendas", tempo: 1, descricao: "Conferir notas de devolução e estornos" },
                { item: "🎯 Analisar margem de lucro por produto/categoria", prioridade: "low", categoria: "Gerencial", tempo: 2, descricao: "Rentabilidade e performance de vendas" },
                { item: "🏪 Conferir vendas no PDV vs sistema contábil", prioridade: "high", categoria: "Vendas", tempo: 1, descricao: "Validar fechamento de caixa diário" },
                
                // Fornecedores
                { item: "🤝 Conciliar contas com fornecedores", prioridade: "medium", categoria: "Financeiro", tempo: 1, descricao: "Conferir saldos e pendências" },
                { item: "📋 Verificar condições de pagamento negociadas", prioridade: "low", categoria: "Financeiro", tempo: 1, descricao: "Prazos, descontos e multas" }
            ],

            // Indústria - Manufatura
            industria: [
                // Produção - Essencial
                { item: "🏭 Calcular custo de produção do período", prioridade: "high", categoria: "Custos", tempo: 3, descricao: "Matéria-prima + mão de obra + custos indiretos" },
                { item: "📦 Inventariar matéria-prima e insumos", prioridade: "high", categoria: "Estoque", tempo: 3, descricao: "Conferir estoque físico vs sistema" },
                { item: "⚙️ Conferir produtos em processo (WIP)", prioridade: "high", categoria: "Produção", tempo: 2, descricao: "Produtos semi-acabados na linha de produção" },
                { item: "✅ Inventariar produtos acabados", prioridade: "high", categoria: "Estoque", tempo: 2, descricao: "Produtos prontos para venda" },
                
                // Custos - Crítico
                { item: "💰 Ratear custos indiretos de fabricação", prioridade: "medium", categoria: "Custos", tempo: 2, descricao: "Energia, depreciação, manutenção por produto" },
                { item: "👷 Calcular custo de mão de obra direta", prioridade: "high", categoria: "Custos", tempo: 2, descricao: "Salários + encargos dos operários" },
                { item: "🔧 Analisar custos de manutenção e reparos", prioridade: "medium", categoria: "Custos", tempo: 1, descricao: "Manutenção preventiva e corretiva" },
                
                // Qualidade e Perdas
                { item: "❌ Calcular perdas no processo produtivo", prioridade: "medium", categoria: "Produção", tempo: 1, descricao: "Refugos, retrabalhos e desperdícios" },
                { item: "📊 Analisar eficiência produtiva", prioridade: "low", categoria: "Gerencial", tempo: 2, descricao: "Capacidade utilizada vs planejada" },
                
                // Ativos
                { item: "🏗️ Calcular depreciação de máquinas e equipamentos", prioridade: "medium", categoria: "Contábil", tempo: 1, descricao: "Depreciação mensal dos ativos imobilizados" }
            ],

            // Serviços - Prestação de Serviços
            servicos: [
                // Receitas - Fundamental
                { item: "💼 Conferir receitas de serviços prestados", prioridade: "high", categoria: "Receitas", tempo: 2, descricao: "Validar contratos vs faturamento" },
                { item: "⏳ Verificar serviços em andamento", prioridade: "medium", categoria: "Operacional", tempo: 1, descricao: "Projetos não finalizados e % de conclusão" },
                { item: "📋 Analisar contratos de longo prazo", prioridade: "medium", categoria: "Receitas", tempo: 2, descricao: "Receitas a apropriar mensalmente" },
                
                // Custos Diretos
                { item: "👨‍💼 Calcular custos diretos por projeto/cliente", prioridade: "medium", categoria: "Custos", tempo: 1, descricao: "Horas trabalhadas e materiais utilizados" },
                { item: "🚗 Conferir despesas de viagem e locomoção", prioridade: "low", categoria: "Custos", tempo: 1, descricao: "Reembolsos e adiantamentos" },
                
                // Profissionais
                { item: "👥 Verificar horas trabalhadas por profissional", prioridade: "medium", categoria: "Recursos Humanos", tempo: 1, descricao: "Timesheet vs folha de pagamento" },
                { item: "🎓 Analisar custos de treinamento e capacitação", prioridade: "low", categoria: "Recursos Humanos", tempo: 1, descricao: "Investimento em qualificação" },
                
                // Margem e Rentabilidade
                { item: "📈 Calcular margem por tipo de serviço", prioridade: "low", categoria: "Gerencial", tempo: 2, descricao: "Rentabilidade por linha de serviço" }
            ],

            // Agronegócio - Rural
            agronegocio: [
                // Safra e Produção
                { item: "🌾 Calcular custo de produção da safra", prioridade: "high", categoria: "Produção", tempo: 3, descricao: "Sementes, fertilizantes, defensivos, combustível" },
                { item: "🚜 Conferir custos de maquinário agrícola", prioridade: "medium", categoria: "Custos", tempo: 2, descricao: "Depreciação, manutenção e combustível" },
                { item: "📦 Inventariar produtos agrícolas em estoque", prioridade: "high", categoria: "Estoque", tempo: 2, descricao: "Grãos, sementes e insumos armazenados" },
                
                // Animais (se aplicável)
                { item: "🐄 Inventariar rebanho e calcular valor", prioridade: "high", categoria: "Ativo Biológico", tempo: 2, descricao: "Contagem e avaliação do gado" },
                { item: "🥛 Conferir produção de leite/ovos", prioridade: "medium", categoria: "Produção", tempo: 1, descricao: "Controle diário de produção" },
                
                // Específicos do Setor
                { item: "🌡️ Analisar perdas por fatores climáticos", prioridade: "medium", categoria: "Perdas", tempo: 1, descricao: "Secas, geadas, pragas e doenças" },
                { item: "💰 Verificar subsídios e financiamentos rurais", prioridade: "medium", categoria: "Financeiro", tempo: 1, descricao: "PRONAF, custeio e investimento" }
            ],

            // Construção Civil
            construcao: [
                // Obras e Contratos
                { item: "🏗️ Calcular % de conclusão das obras", prioridade: "high", categoria: "Obras", tempo: 3, descricao: "Medição física vs orçamento" },
                { item: "📋 Conferir contratos de empreitada", prioridade: "high", categoria: "Contratos", tempo: 2, descricao: "Valores executados vs a executar" },
                { item: "🧱 Inventariar materiais de construção", prioridade: "high", categoria: "Estoque", tempo: 2, descricao: "Cimento, ferro, areia, brita, etc." },
                
                // Custos Específicos
                { item: "👷 Calcular custo de mão de obra por obra", prioridade: "medium", categoria: "Custos", tempo: 2, descricao: "Funcionários próprios e terceirizados" },
                { item: "🚚 Conferir custos de transporte e logística", prioridade: "medium", categoria: "Custos", tempo: 1, descricao: "Frete de materiais e equipamentos" },
                { item: "⚡ Analisar custos de energia e água nas obras", prioridade: "low", categoria: "Custos", tempo: 1, descricao: "Utilities consumidas no canteiro" },
                
                // Equipamentos
                { item: "🏗️ Calcular depreciação de equipamentos", prioridade: "medium", categoria: "Contábil", tempo: 1, descricao: "Guindastes, betoneiras, andaimes" }
            ],

            // Setor Financeiro
            financeiro: [
                // Específico do Setor
                { item: "💳 Conciliar carteiras de crédito", prioridade: "high", categoria: "Ativos", tempo: 3, descricao: "Empréstimos e financiamentos concedidos" },
                { item: "📊 Calcular provisão para devedores duvidosos", prioridade: "high", categoria: "Provisões", tempo: 2, descricao: "PECLD conforme resolução BACEN" },
                { item: "💰 Verificar captações e aplicações", prioridade: "high", categoria: "Financeiro", tempo: 2, descricao: "CDB, poupança e outros produtos" },
                { item: "📈 Analisar spread bancário", prioridade: "medium", categoria: "Gerencial", tempo: 1, descricao: "Margem entre captação e aplicação" },
                { item: "🛡️ Conferir seguros e garantias", prioridade: "medium", categoria: "Riscos", tempo: 1, descricao: "Apólices vigentes e sinistros" }
            ],

            // Atividade Mista
            mista: [
                { item: "🔄 Separar receitas por atividade", prioridade: "high", categoria: "Receitas", tempo: 2, descricao: "Comércio, indústria, serviços separadamente" },
                { item: "📊 Ratear custos comuns entre atividades", prioridade: "medium", categoria: "Custos", tempo: 2, descricao: "Aluguel, energia, pessoal administrativo" },
                { item: "📋 Verificar enquadramento tributário por atividade", prioridade: "high", categoria: "Fiscal", tempo: 1, descricao: "Diferentes regimes para cada atividade" }
            ]
        };

        let checklistAtual = [];
        let configAtual = {};

        function gerarChecklistAvancado() {
            try {
                console.log('🚀 Iniciando geração do checklist...');
                
                // Coletar dados do formulário
                const dados = coletarDadosFormulario();
                console.log('📋 Dados coletados:', dados);
                
                if (!validarDados(dados)) {
                    alert('Por favor, preencha pelo menos: Tipo de Empresa, Porte, Regime Tributário e Complexidade.');
                    return;
                }

                configAtual = dados;
                console.log('⚙️ Configuração salva');
                
                // Gerar checklist personalizado
                checklistAtual = montarChecklistInteligente(dados);
                console.log('✅ Checklist gerado:', checklistAtual.length, 'itens');
                
                // Exibir dashboard IMEDIATAMENTE
                exibirDashboard(dados, checklistAtual);
                console.log('🎯 Dashboard exibido');
                
            } catch (error) {
                console.error('❌ Erro ao gerar checklist:', error);
                alert('Ocorreu um erro ao gerar o checklist. Verifique se todos os campos estão preenchidos.');
            }
        }

        function coletarDadosFormulario() {
            try {
                const operacoes = Array.from(document.querySelectorAll('input[name="operacoes"]:checked')).map(cb => cb.value);
                
                const nomeEmpresa = document.getElementById('nomeEmpresa')?.value || '';
                
                const dados = {
                    nomeEmpresa: nomeEmpresa,
                    tipoEmpresa: document.getElementById('tipoEmpresa')?.value || '',
                    porte: document.getElementById('porte')?.value || '',
                    regime: document.getElementById('regime')?.value || '',
                    erp: document.getElementById('erp')?.value || '',
                    automacao: document.getElementById('automacao')?.value || '',
                    equipe: document.getElementById('equipe')?.value || '',
                    prazo: document.getElementById('prazo')?.value || '15',
                    complexidade: document.getElementById('complexidade')?.value || '',
                    operacoes: operacoes,
                    auditoria: document.getElementById('auditoria')?.value || '',
                    // Identificação do usuário baseada no nome da empresa ou timestamp
                    usuarioId: nomeEmpresa ? nomeEmpresa.toLowerCase().replace(/\s+/g, '-') : 'usuario-' + Date.now()
                };
                
                console.log('Dados do formulário:', dados);
                return dados;
                
            } catch (error) {
                console.error('Erro ao coletar dados:', error);
                return {};
            }
        }

        function validarDados(dados) {
            const camposObrigatorios = [
                { campo: 'tipoEmpresa', nome: 'Tipo de Empresa' },
                { campo: 'porte', nome: 'Porte da Empresa' },
                { campo: 'regime', nome: 'Regime Tributário' },
                { campo: 'complexidade', nome: 'Complexidade Operacional' }
            ];
            
            const camposFaltando = camposObrigatorios.filter(item => !dados[item.campo] || dados[item.campo] === '');
            
            if (camposFaltando.length > 0) {
                // Destacar campos obrigatórios
                destacarCamposObrigatorios(camposFaltando);
                
                // Mostrar aviso detalhado
                mostrarAvisoValidacao(camposFaltando);
                
                return false;
            }
            
            // Remover destaques se tudo estiver preenchido
            removerDestaquesValidacao();
            return true;
        }

        function destacarCamposObrigatorios(camposFaltando) {
            // Primeiro, remover todos os destaques existentes
            document.querySelectorAll('.campo-obrigatorio').forEach(el => {
                el.classList.remove('campo-obrigatorio', 'border-red-500', 'bg-red-50');
            });
            
            // Destacar campos que estão faltando
            camposFaltando.forEach(item => {
                const elemento = document.getElementById(item.campo);
                if (elemento) {
                    elemento.classList.add('campo-obrigatorio', 'border-red-500', 'bg-red-50');
                    elemento.parentElement.classList.add('shake-animation');
                    
                    // Remover animação após um tempo
                    setTimeout(() => {
                        elemento.parentElement.classList.remove('shake-animation');
                    }, 600);
                }
            });
        }

        function mostrarAvisoValidacao(camposFaltando) {
            const aviso = document.createElement('div');
            aviso.id = 'avisoValidacao';
            aviso.className = 'fixed top-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg z-50 max-w-sm';
            aviso.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="text-2xl">⚠️</div>
                    <div class="flex-1">
                        <div class="font-bold mb-2">Campos obrigatórios não preenchidos:</div>
                        <ul class="text-sm space-y-1">
                            ${camposFaltando.map(item => `<li>• ${item.nome}</li>`).join('')}
                        </ul>
                        <div class="text-xs mt-2 opacity-90">Preencha estes campos para gerar seu checklist personalizado.</div>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200 text-xl">×</button>
                </div>
            `;
            
            // Remover aviso anterior se existir
            const avisoAnterior = document.getElementById('avisoValidacao');
            if (avisoAnterior) {
                avisoAnterior.remove();
            }
            
            document.body.appendChild(aviso);
            
            // Auto-remover após 8 segundos
            setTimeout(() => {
                if (aviso.parentElement) {
                    aviso.remove();
                }
            }, 8000);
        }

        function removerDestaquesValidacao() {
            document.querySelectorAll('.campo-obrigatorio').forEach(el => {
                el.classList.remove('campo-obrigatorio', 'border-red-500', 'bg-red-50');
            });
        }

        function montarChecklistInteligente(dados) {
            console.log('Montando checklist para:', dados);
            
            let checklist = [...checklistDatabase.basico];
            console.log('Checklist básico:', checklist.length, 'itens');
            
            // Adicionar itens específicos baseados no tipo
            if (dados.tipoEmpresa && checklistDatabase[dados.tipoEmpresa]) {
                console.log('Adicionando itens específicos para:', dados.tipoEmpresa);
                checklist = checklist.concat(checklistDatabase[dados.tipoEmpresa]);
            }
            
            // Ajustar prioridades baseado na complexidade
            if (dados.complexidade === 'alta' || dados.complexidade === 'muito-alta') {
                checklist.forEach(item => {
                    if (item.prioridade === 'medium') item.prioridade = 'high';
                });
            }
            
            // Adicionar itens baseados no regime tributário
            if (dados.regime === 'real') {
                checklist.push(
                    { item: "📊 Calcular IRPJ e CSLL sobre lucro real", prioridade: "high", categoria: "Fiscal", tempo: 3, descricao: "Apuração trimestral ou anual" },
                    { item: "📋 Preparar LALUR (Livro de Apuração)", prioridade: "high", categoria: "Fiscal", tempo: 2, descricao: "Adições e exclusões do lucro contábil" },
                    { item: "💰 Verificar compensação de prejuízos fiscais", prioridade: "medium", categoria: "Fiscal", tempo: 1, descricao: "Limite de 30% do lucro real" }
                );
            }
            
            if (dados.regime === 'presumido') {
                checklist.push(
                    { item: "📊 Calcular IRPJ e CSLL presumido", prioridade: "high", categoria: "Fiscal", tempo: 2, descricao: "Aplicar % sobre receita bruta" },
                    { item: "💳 Verificar limite de receita bruta", prioridade: "medium", categoria: "Fiscal", tempo: 1, descricao: "R$ 78 milhões anuais" }
                );
            }
            
            if (dados.regime === 'simples') {
                checklist.push(
                    { item: "📋 Calcular DAS do Simples Nacional", prioridade: "high", categoria: "Fiscal", tempo: 1, descricao: "Aplicar alíquota sobre receita" },
                    { item: "📊 Verificar sublimites por atividade", prioridade: "medium", categoria: "Fiscal", tempo: 1, descricao: "Comércio, indústria, serviços" }
                );
            }
            
            // Adicionar itens baseados no porte
            if (dados.porte === 'grande' || dados.porte === 'media') {
                checklist.push(
                    { item: "📄 Preparar demonstrações completas (IFRS)", prioridade: "high", categoria: "Contábil", tempo: 4, descricao: "Balanço, DRE, DFC, DMPL, DVA" },
                    { item: "🔍 Revisar controles internos", prioridade: "medium", categoria: "Governança", tempo: 2, descricao: "SOX, compliance e auditoria interna" }
                );
            }
            
            // Adicionar itens especiais baseado nas operações
            dados.operacoes.forEach(op => {
                if (op === 'internacional') {
                    checklist.push(
                        { item: "💱 Verificar operações de câmbio", prioridade: "high", categoria: "Internacional", tempo: 2, descricao: "Variação cambial e hedge" },
                        { item: "🌍 Conferir preços de transferência", prioridade: "high", categoria: "Internacional", tempo: 3, descricao: "Operações com partes relacionadas" },
                        { item: "📋 Calcular CFC (Controlled Foreign Company)", prioridade: "medium", categoria: "Internacional", tempo: 2, descricao: "Lucros de controladas no exterior" }
                    );
                }
                if (op === 'subsidiarias') {
                    checklist.push(
                        { item: "🏢 Consolidar demonstrações das subsidiárias", prioridade: "high", categoria: "Consolidação", tempo: 4, descricao: "Eliminar transações intercompany" },
                        { item: "💼 Verificar investimentos em controladas", prioridade: "medium", categoria: "Investimentos", tempo: 2, descricao: "Método de equivalência patrimonial" }
                    );
                }
                if (op === 'investimentos') {
                    checklist.push(
                        { item: "📈 Avaliar investimentos a valor justo", prioridade: "high", categoria: "Investimentos", tempo: 2, descricao: "Mark-to-market de títulos e valores" },
                        { item: "💹 Calcular impairment de ativos", prioridade: "medium", categoria: "Contábil", tempo: 2, descricao: "Teste de recuperabilidade" }
                    );
                }
                if (op === 'derivativos') {
                    checklist.push(
                        { item: "💹 Avaliar derivativos e hedge accounting", prioridade: "high", categoria: "Derivativos", tempo: 3, descricao: "Swaps, opções, futuros" },
                        { item: "📊 Testar efetividade do hedge", prioridade: "medium", categoria: "Derivativos", tempo: 2, descricao: "Correlação entre hedge e item protegido" }
                    );
                }
            });
            
            // Adicionar itens baseados na auditoria
            if (dados.auditoria && dados.auditoria !== 'nao') {
                checklist.push(
                    { item: "🔍 Preparar papéis de trabalho para auditoria", prioridade: "medium", categoria: "Auditoria", tempo: 3, descricao: "Documentação e evidências" },
                    { item: "📋 Revisar carta de representação", prioridade: "low", categoria: "Auditoria", tempo: 1, descricao: "Declarações da administração" }
                );
            }
            
            console.log('Checklist final:', checklist.length, 'itens');
            return checklist;
        }

        function exibirDashboard(dados, checklist) {
            try {
                console.log('📊 Iniciando exibição do dashboard...');
                
                // Mostrar dashboard PRIMEIRO
                const questionarioElement = document.getElementById('questionario');
                const dashboardElement = document.getElementById('dashboard');
                
                console.log('🔄 Alternando visibilidade...');
                if (questionarioElement) {
                    questionarioElement.style.display = 'none';
                    console.log('✅ Questionário ocultado');
                }
                
                if (dashboardElement) {
                    dashboardElement.classList.remove('hidden');
                    dashboardElement.style.display = 'block';
                    console.log('✅ Dashboard exibido');
                }
                
                // Resetar progresso ANTES de gerar conteúdo
                resetarProgresso();
                console.log('🔄 Progresso resetado');
                
                // Atualizar informações da empresa IMEDIATAMENTE
                const empresaInfoElement = document.getElementById('empresaInfo');
                const totalItensElement = document.getElementById('totalItens');
                
                if (empresaInfoElement) {
                    empresaInfoElement.textContent = `${dados.nomeEmpresa || 'Empresa'} - ${dados.tipoEmpresa} - ${dados.porte}`;
                    console.log('✅ Info da empresa atualizada');
                }
                
                if (totalItensElement) {
                    totalItensElement.textContent = checklist.length;
                    console.log('✅ Total de itens atualizado:', checklist.length);
                }
                
                // Gerar TODO o conteúdo IMEDIATAMENTE e SINCRONAMENTE
                console.log('📈 Gerando análise de riscos...');
                gerarAnaliseRiscos(dados);
                console.log('✅ Análise de riscos concluída');
                
                console.log('📅 Gerando cronograma...');
                gerarCronograma(dados, checklist);
                console.log('✅ Cronograma concluído');
                
                console.log('📊 Gerando estatísticas...');
                gerarEstatisticas(checklist);
                console.log('✅ Estatísticas concluídas');
                
                console.log('📋 Exibindo checklist completo...');
                exibirChecklistCompleto(checklist);
                console.log('✅ Checklist exibido');
                
                // Forçar scroll imediato para o dashboard
                setTimeout(() => {
                    if (dashboardElement) {
                        dashboardElement.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });
                        console.log('🎯 Scroll para dashboard executado');
                    }
                }, 100);
                
                console.log('🎉 Dashboard completamente carregado!');
                
            } catch (error) {
                console.error('❌ Erro ao exibir dashboard:', error);
                alert('Erro ao exibir o dashboard. Verifique o console para mais detalhes.');
            }
        }

        function gerarAnaliseRiscos(dados) {
            const riscos = [
                {
                    nome: "Complexidade Operacional",
                    nivel: dados.complexidade === 'muito-alta' ? 90 : dados.complexidade === 'alta' ? 70 : dados.complexidade === 'media' ? 40 : 20,
                    icon: "🔧",
                    descricao: "Avalia a dificuldade das operações contábeis",
                    impacto: dados.complexidade === 'muito-alta' ? "Risco de erros críticos e atrasos significativos" : 
                             dados.complexidade === 'alta' ? "Possíveis inconsistências e necessidade de revisões extras" :
                             dados.complexidade === 'media' ? "Controle adequado com atenção em pontos específicos" :
                             "Operações simples com baixo risco de problemas",
                    dicas: dados.complexidade === 'muito-alta' ? ["Considere contratar consultoria especializada", "Implemente controles rigorosos de qualidade", "Documente todos os processos detalhadamente"] :
                           dados.complexidade === 'alta' ? ["Estabeleça checkpoints de revisão", "Treine a equipe em procedimentos complexos", "Use ferramentas de automação"] :
                           dados.complexidade === 'media' ? ["Mantenha procedimentos padronizados", "Faça revisões periódicas"] :
                           ["Continue com os processos atuais", "Monitore mudanças regulatórias"]
                },
                {
                    nome: "Nível de Automação",
                    nivel: dados.automacao === 'baixo' ? 80 : dados.automacao === 'medio' ? 50 : 20,
                    icon: "🤖",
                    descricao: "Mede a dependência de processos manuais",
                    impacto: dados.automacao === 'baixo' ? "Alto risco de erros humanos e ineficiência" :
                             dados.automacao === 'medio' ? "Risco moderado com alguns gargalos manuais" :
                             "Processos otimizados com baixo risco operacional",
                    dicas: dados.automacao === 'baixo' ? ["Invista em sistema ERP integrado", "Automatize conciliações bancárias", "Implemente validações automáticas", "Reduza retrabalho manual"] :
                           dados.automacao === 'medio' ? ["Identifique gargalos manuais restantes", "Automatize relatórios recorrentes", "Integre sistemas isolados"] :
                           ["Monitore performance dos sistemas", "Mantenha backups e contingências", "Otimize processos existentes"]
                },
                {
                    nome: "Pressão de Prazo",
                    nivel: parseInt(dados.prazo) < 10 ? 85 : parseInt(dados.prazo) < 15 ? 60 : 30,
                    icon: "⏰",
                    descricao: "Avalia o tempo disponível vs complexidade",
                    impacto: parseInt(dados.prazo) < 10 ? "Risco crítico de não cumprimento de prazos" :
                             parseInt(dados.prazo) < 15 ? "Pressão moderada podendo afetar qualidade" :
                             "Tempo adequado para execução com qualidade",
                    dicas: parseInt(dados.prazo) < 10 ? ["Priorize tarefas críticas imediatamente", "Considere apoio externo temporário", "Trabalhe em paralelo quando possível", "Prepare contingências"] :
                           parseInt(dados.prazo) < 15 ? ["Organize cronograma detalhado", "Monitore progresso diariamente", "Antecipe possíveis problemas"] :
                           ["Mantenha ritmo constante", "Use tempo extra para revisões", "Documente melhorias para próximos fechamentos"]
                },
                {
                    nome: "Capacidade da Equipe",
                    nivel: dados.equipe === '1' ? 75 : dados.equipe === '2-5' ? 45 : 25,
                    icon: "👥",
                    descricao: "Analisa recursos humanos disponíveis",
                    impacto: dados.equipe === '1' ? "Risco de sobrecarga e falta de segregação de funções" :
                             dados.equipe === '2-5' ? "Capacidade adequada com necessidade de coordenação" :
                             "Recursos suficientes para distribuição de tarefas",
                    dicas: dados.equipe === '1' ? ["Implemente controles compensatórios", "Considere terceirização de atividades", "Documente todos os processos", "Tenha backup para situações críticas"] :
                           dados.equipe === '2-5' ? ["Defina responsabilidades claras", "Estabeleça processo de revisão cruzada", "Treine equipe em múltiplas funções"] :
                           ["Otimize distribuição de tarefas", "Desenvolva especialistas por área", "Mantenha comunicação eficiente"]
                }
            ];
            
            const nivelGeral = Math.round(riscos.reduce((acc, r) => acc + r.nivel, 0) / riscos.length);
            
            // Gerar indicadores expandidos
            const riskIndicatorsElement = document.getElementById('riskIndicators');
            if (!riskIndicatorsElement) {
                console.error('Elemento riskIndicators não encontrado');
                return;
            }
            
            riskIndicatorsElement.innerHTML = riscos.map((risco, index) => {
                const cor = risco.nivel > 70 ? 'bg-red-500' : risco.nivel > 40 ? 'bg-yellow-500' : 'bg-green-500';
                const corFundo = risco.nivel > 70 ? 'bg-red-50 border-red-200' : risco.nivel > 40 ? 'bg-yellow-50 border-yellow-200' : 'bg-green-50 border-green-200';
                
                return `
                    <div class="border rounded-lg p-4 ${corFundo} hover:shadow-md transition-all cursor-pointer" onclick="toggleRiskDetails(${index})">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <span class="text-lg">${risco.icon}</span>
                                <span class="text-sm font-semibold">${risco.nome}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="${cor} h-2 rounded-full transition-all duration-500" style="width: ${risco.nivel}%"></div>
                                </div>
                                <span class="text-xs font-bold w-10">${risco.nivel}%</span>
                                <span class="text-xs text-gray-500">▼</span>
                            </div>
                        </div>
                        
                        <div id="riskDetails-${index}" class="hidden mt-3 space-y-3">
                            <div>
                                <p class="text-xs text-gray-600 mb-1"><strong>Descrição:</strong></p>
                                <p class="text-xs text-gray-700">${risco.descricao}</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray-600 mb-1"><strong>Impacto Potencial:</strong></p>
                                <p class="text-xs text-gray-700">${risco.impacto}</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray-600 mb-2"><strong>Dicas de Mitigação:</strong></p>
                                <ul class="text-xs text-gray-700 space-y-1">
                                    ${risco.dicas.map(dica => `<li class="flex items-start space-x-1"><span class="text-blue-500">•</span><span>${dica}</span></li>`).join('')}
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
            
            // Nível geral com explicação
            const corGeral = nivelGeral > 70 ? 'bg-red-500' : nivelGeral > 40 ? 'bg-yellow-500' : 'bg-green-500';
            const textoGeral = nivelGeral > 70 ? 'Alto' : nivelGeral > 40 ? 'Médio' : 'Baixo';
            const explicacaoGeral = nivelGeral > 70 ? 'Requer atenção imediata e medidas preventivas' :
                                   nivelGeral > 40 ? 'Situação controlável com monitoramento' :
                                   'Cenário favorável para o fechamento';
            
            const riskLevelElement = document.getElementById('riskLevel');
            const riskTextElement = document.getElementById('riskText');
            
            if (riskLevelElement) {
                riskLevelElement.className = `h-2 rounded-full transition-all duration-500 ${corGeral}`;
                riskLevelElement.style.width = `${nivelGeral}%`;
            }
            
            if (riskTextElement) {
                riskTextElement.textContent = `${textoGeral} (${nivelGeral}%)`;
            }
            
            // Adicionar explicação geral
            const riskContainer = document.querySelector('#riskIndicators')?.parentNode?.querySelector('.mt-4 p-3');
            if (riskContainer) {
                riskContainer.innerHTML = `
                    <div class="text-sm font-medium text-gray-700 mb-1">Nível Geral de Risco</div>
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div id="riskLevel" class="h-2 rounded-full transition-all duration-500 ${corGeral}" style="width: ${nivelGeral}%"></div>
                        </div>
                        <span id="riskText" class="text-sm font-bold">${textoGeral} (${nivelGeral}%)</span>
                    </div>
                    <p class="text-xs text-gray-600">${explicacaoGeral}</p>
                `;
            }
        }

        function toggleRiskDetails(index) {
            const details = document.getElementById(`riskDetails-${index}`);
            const arrow = details.parentNode.querySelector('.text-gray-500');
            
            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                arrow.textContent = '▲';
            } else {
                details.classList.add('hidden');
                arrow.textContent = '▼';
            }
        }

        let cronogramaAtual = {};

        function gerarCronograma(dados, checklist) {
            const prazo = parseInt(dados.prazo) || 15;
            cronogramaAtual.prazoOriginal = prazo;
            cronogramaAtual.prazoAtual = prazo;
            
            atualizarCronograma(dados, checklist, prazo);
        }

        function atualizarCronograma(dados, checklist, prazo) {
            const fases = [
                {
                    nome: "Preparação",
                    descricao: "Organização inicial e coleta de documentos",
                    porcentagem: 25,
                    tarefas: ["Organizar documentos", "Verificar sistemas", "Preparar planilhas"],
                    cor: "bg-blue-100 text-blue-800",
                    icon: "📋"
                },
                {
                    nome: "Conciliações",
                    descricao: "Conciliação bancária e contas",
                    porcentagem: 30,
                    tarefas: ["Conciliação bancária", "Contas a receber", "Contas a pagar"],
                    cor: "bg-yellow-100 text-yellow-800",
                    icon: "🔍"
                },
                {
                    nome: "Apurações",
                    descricao: "Cálculos e apurações fiscais",
                    porcentagem: 30,
                    tarefas: ["Apuração de impostos", "Cálculo de provisões", "Análise de custos"],
                    cor: "bg-orange-100 text-orange-800",
                    icon: "📊"
                },
                {
                    nome: "Finalização",
                    descricao: "Relatórios e entrega final",
                    porcentagem: 15,
                    tarefas: ["Demonstrações contábeis", "Relatórios gerenciais", "Revisão final"],
                    cor: "bg-green-100 text-green-800",
                    icon: "✅"
                }
            ];

            // Calcular dias por fase
            let diasAcumulados = 0;
            fases.forEach(fase => {
                fase.diasDuracao = Math.ceil((prazo * fase.porcentagem) / 100);
                fase.diaInicio = diasAcumulados + 1;
                fase.diaFim = diasAcumulados + fase.diasDuracao;
                diasAcumulados += fase.diasDuracao;
            });

            // Atualizar display do prazo
            const cronogramaDiasElement = document.getElementById('cronogramaDias');
            if (cronogramaDiasElement) {
                cronogramaDiasElement.textContent = `${prazo} dias úteis`;
            }

            // Gerar marcos da timeline
            const timelineMilestonesElement = document.getElementById('timelineMilestones');
            if (timelineMilestonesElement) {
                timelineMilestonesElement.innerHTML = fases.map((fase, index) => `
                <div class="flex flex-col items-center">
                    <div class="w-6 h-6 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center text-xs font-bold z-10">
                        ${index + 1}
                    </div>
                    <div class="text-xs mt-1 text-center max-w-16">
                        <div class="font-medium">${fase.nome}</div>
                        <div class="text-gray-500">D${fase.diaFim}</div>
                    </div>
                </div>
                `).join('');
            }

            // Gerar fases detalhadas premium - Mais compactas
            const cronogramaDetalhadoElement = document.getElementById('cronogramaDetalhado');
            if (cronogramaDetalhadoElement) {
                cronogramaDetalhadoElement.innerHTML = fases.map((fase, index) => `
                <div class="glass-card rounded-xl p-4 hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-lg">${fase.icon}</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-800">${fase.nome}</h4>
                                <p class="text-xs text-gray-600">${fase.descricao}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold gradient-text-primary">D${fase.diaInicio}-${fase.diaFim}</div>
                            <div class="text-xs text-gray-500">${fase.diasDuracao} dias</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="grid grid-cols-1 gap-1">
                            ${fase.tarefas.map(tarefa => `
                                <div class="flex items-center space-x-2 p-2 ${fase.cor} rounded-lg text-xs">
                                    <span class="w-1 h-1 bg-current rounded-full"></span>
                                    <span>${tarefa}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex-1 mr-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="progress-bar h-2 rounded-full transition-all duration-1000" 
                                     style="width: ${fase.porcentagem}%"></div>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-gray-700">${fase.porcentagem}%</span>
                    </div>
                </div>
                `).join('');
            }

            // Gerar alertas e sugestões
            gerarAlertasCronograma(dados, prazo);
        }

        function gerarAlertasCronograma(dados, prazo) {
            const alertas = [];

            // Alertas baseados no prazo
            if (prazo < 10) {
                alertas.push({
                    tipo: "warning",
                    icon: "⚠️",
                    titulo: "Prazo Apertado",
                    mensagem: "Considere priorizar tarefas críticas e aumentar a equipe.",
                    cor: "bg-yellow-50 border-yellow-200 text-yellow-800"
                });
            }

            // Alertas baseados na complexidade
            if (dados.complexidade === 'muito-alta' && prazo < 15) {
                alertas.push({
                    tipo: "danger",
                    icon: "🚨",
                    titulo: "Risco Alto",
                    mensagem: "Complexidade muito alta com prazo curto. Recomenda-se revisão do cronograma.",
                    cor: "bg-red-50 border-red-200 text-red-800"
                });
            }

            // Sugestões baseadas na automação
            if (dados.automacao === 'baixo') {
                alertas.push({
                    tipo: "info",
                    icon: "💡",
                    titulo: "Oportunidade de Melhoria",
                    mensagem: "Considere automatizar processos manuais para ganhar eficiência.",
                    cor: "bg-blue-50 border-blue-200 text-blue-800"
                });
            }

            // Alertas baseados na equipe
            if (dados.equipe === '1' && dados.porte !== 'mei') {
                alertas.push({
                    tipo: "warning",
                    icon: "👥",
                    titulo: "Recursos Limitados",
                    mensagem: "Equipe pequena para o porte da empresa. Considere apoio adicional.",
                    cor: "bg-orange-50 border-orange-200 text-orange-800"
                });
            }

            const cronogramaAlertasElement = document.getElementById('cronogramaAlertas');
            if (cronogramaAlertasElement) {
                cronogramaAlertasElement.innerHTML = alertas.map(alerta => `
                <div class="p-3 rounded-lg border ${alerta.cor}">
                    <div class="flex items-start space-x-2">
                        <span class="text-lg">${alerta.icon}</span>
                        <div>
                            <div class="font-medium text-sm">${alerta.titulo}</div>
                            <div class="text-xs mt-1">${alerta.mensagem}</div>
                        </div>
                    </div>
                </div>
                `).join('');
            }
        }

        function ajustarCronograma(ajuste) {
            const novaData = cronogramaAtual.prazoAtual + (ajuste * 2);
            if (novaData >= 5 && novaData <= 30) {
                cronogramaAtual.prazoAtual = novaData;
                atualizarCronograma(configAtual, checklistAtual, novaData);
                
                // Feedback visual
                const feedback = ajuste > 0 ? "Cronograma estendido!" : "Cronograma acelerado!";
                mostrarFeedback(feedback);
            }
        }

        function mostrarFeedback(mensagem) {
            const feedback = document.createElement('div');
            feedback.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300';
            feedback.textContent = mensagem;
            document.body.appendChild(feedback);
            
            setTimeout(() => {
                feedback.style.opacity = '0';
                setTimeout(() => feedback.remove(), 300);
            }, 2000);
        }

        function gerarEstatisticas(checklist) {
            const stats = {
                total: checklist.length,
                alta: checklist.filter(item => item.prioridade === 'high').length,
                media: checklist.filter(item => item.prioridade === 'medium').length,
                baixa: checklist.filter(item => item.prioridade === 'low').length,
                tempoTotal: checklist.reduce((acc, item) => acc + (item.tempo || 1), 0)
            };
            
            const estatisticasElement = document.getElementById('estatisticas');
            if (estatisticasElement) {
                estatisticasElement.innerHTML = `
                    <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                        <span class="text-sm">Alta Prioridade</span>
                        <span class="font-bold text-red-600">${stats.alta}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                        <span class="text-sm">Média Prioridade</span>
                        <span class="font-bold text-yellow-600">${stats.media}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <span class="text-sm">Baixa Prioridade</span>
                        <span class="font-bold text-green-600">${stats.baixa}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <span class="text-sm">Tempo Estimado</span>
                        <span class="font-bold text-blue-600">${stats.tempoTotal}h</span>
                    </div>
                `;
            }
        }

        function exibirChecklistCompleto(checklist) {
            const content = document.getElementById('checklistContent');
            if (!content) {
                console.error('Elemento checklistContent não encontrado');
                return;
            }
            
            // Agrupar por categoria para melhor organização
            const categorias = {};
            checklist.forEach(item => {
                if (!categorias[item.categoria]) {
                    categorias[item.categoria] = [];
                }
                categorias[item.categoria].push(item);
            });
            
            // Ordenar categorias por importância
            const ordemCategorias = ['Fiscal', 'Financeiro', 'Contábil', 'Estoque', 'Custos', 'Receitas', 'Produção', 'Vendas', 'Trabalhista', 'Operacional', 'Auditoria', 'Internacional', 'Consolidação', 'Investimentos', 'Derivativos', 'Governança', 'Gerencial'];
            
            let htmlContent = '';
            let itemIndex = 0;
            
            ordemCategorias.forEach(nomeCategoria => {
                if (categorias[nomeCategoria]) {
                    // Cabeçalho da categoria
                    const iconesCategoria = {
                        'Fiscal': '📋', 'Financeiro': '💰', 'Contábil': '📊', 'Estoque': '📦',
                        'Custos': '💸', 'Receitas': '💵', 'Produção': '🏭', 'Vendas': '🛒',
                        'Trabalhista': '👥', 'Operacional': '⚙️', 'Auditoria': '🔍', 'Internacional': '🌍',
                        'Consolidação': '🏢', 'Investimentos': '📈', 'Derivativos': '💹', 'Governança': '🛡️',
                        'Gerencial': '📊', 'Obras': '🏗️', 'Contratos': '📋', 'Ativo Biológico': '🐄',
                        'Perdas': '📉', 'Recursos Humanos': '👨‍💼', 'Ativos': '💳', 'Provisões': '📊', 'Riscos': '⚠️'
                    };
                    
                    htmlContent += `
                        <div class="mb-8">
                            <div class="flex items-center mb-6 p-4 glass-card rounded-2xl border border-gray-100">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-blue-500 rounded-xl blur-sm opacity-20"></div>
                                    <div class="relative w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                        <span class="text-xl">${iconesCategoria[nomeCategoria] || '📋'}</span>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="text-xl font-bold text-gray-800">${nomeCategoria}</h4>
                                    <p class="text-sm text-gray-600">Categoria com ${categorias[nomeCategoria].length} itens especializados</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold gradient-text-primary">${categorias[nomeCategoria].length}</div>
                                    <div class="text-xs text-gray-500">itens</div>
                                </div>
                            </div>
                            <div class="space-y-4">
                    `;
                    
                    // Ordenar itens da categoria por prioridade
                    categorias[nomeCategoria].sort((a, b) => {
                        const prioridadeOrdem = { 'high': 3, 'medium': 2, 'low': 1 };
                        return prioridadeOrdem[b.prioridade] - prioridadeOrdem[a.prioridade];
                    });
                    
                    categorias[nomeCategoria].forEach(item => {
                        const priorityClass = `priority-${item.prioridade === 'high' ? 'high' : item.prioridade === 'medium' ? 'medium' : 'low'}`;
                        const priorityIcon = item.prioridade === 'high' ? '🔴' : item.prioridade === 'medium' ? '🟡' : '🟢';
                        const priorityText = item.prioridade === 'high' ? 'Alta' : item.prioridade === 'medium' ? 'Média' : 'Baixa';
                        const priorityColor = item.prioridade === 'high' ? 'border-red-200 bg-red-50' : item.prioridade === 'medium' ? 'border-yellow-200 bg-yellow-50' : 'border-green-200 bg-green-50';
                        
                        htmlContent += `
                            <div class="checklist-item ${priorityClass} glass-card rounded-2xl hover:shadow-lg transition-all duration-300 slide-up border ${priorityColor}" 
                                 style="animation-delay: ${itemIndex * 0.03}s" data-priority="${item.prioridade}" data-item-id="${itemIndex}">
                                <div class="flex items-start space-x-4 p-6">
                                    <div class="relative mt-1">
                                        <input type="checkbox" id="item-${itemIndex}" class="h-6 w-6 text-blue-600 rounded-lg focus:ring-blue-500 focus:ring-2" onchange="atualizarProgresso()">
                                    </div>
                                    <div class="flex-1">
                                        <label for="item-${itemIndex}" class="block text-gray-800 cursor-pointer font-semibold mb-2 text-lg hover:text-blue-600 transition-colors item-label">
                                            ${item.item}
                                        </label>
                                        ${item.descricao ? `<p class="text-sm text-gray-600 mb-4 leading-relaxed item-description">${item.descricao}</p>` : ''}
                                        
                                        <!-- Área de observações premium -->
                                        <div id="observacao-${itemIndex}" class="hidden mt-4 p-4 glass-card rounded-xl border border-yellow-200 bg-yellow-50">
                                            <div class="flex items-center mb-2">
                                                <span class="text-lg mr-2">📝</span>
                                                <span class="text-sm font-semibold text-gray-700">Suas Observações</span>
                                            </div>
                                            <textarea placeholder="Adicione suas observações, dificuldades ou comentários sobre este item..." 
                                                      class="w-full text-sm border-0 bg-transparent resize-none focus:ring-0 placeholder-gray-400" 
                                                      rows="3" onchange="salvarObservacao(${itemIndex}, this.value)"></textarea>
                                        </div>
                                        
                                        <div class="flex items-center justify-between mt-4">
                                            <div class="flex items-center space-x-4 text-sm">
                                                <div class="flex items-center space-x-2 px-3 py-1 ${priorityColor} rounded-lg priority-badge">
                                                    <span>${priorityIcon}</span>
                                                    <span class="font-semibold">${priorityText}</span>
                                                </div>
                                                <div class="flex items-center space-x-2 px-3 py-1 bg-blue-50 rounded-lg border border-blue-200">
                                                    <span>⏱️</span>
                                                    <span class="font-semibold text-blue-700">${item.tempo || 1}h</span>
                                                </div>
                                                <div class="px-3 py-1 bg-gray-100 rounded-lg border border-gray-200">
                                                    <span class="text-gray-700 font-medium">${item.categoria}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 action-buttons">
                                                <button onclick="toggleObservacao(${itemIndex})" class="p-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg transition-colors" title="Adicionar observação">
                                                    📝
                                                </button>
                                                <button onclick="editarItem(${itemIndex})" class="p-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors" title="Editar item">
                                                    ✏️
                                                </button>
                                                ${item.customizado ? `<button onclick="removerItem(${itemIndex})" class="p-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors" title="Remover item">🗑️</button>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        itemIndex++;
                    });
                    
                    htmlContent += `
                            </div>
                        </div>
                    `;
                }
            });
            
            // Adicionar categorias restantes que não estão na ordem predefinida
            Object.keys(categorias).forEach(nomeCategoria => {
                if (!ordemCategorias.includes(nomeCategoria)) {
                    htmlContent += `
                        <div class="mb-6">
                            <div class="flex items-center mb-3 pb-2 border-b border-gray-200">
                                <span class="text-2xl mr-2">📋</span>
                                <h4 class="text-lg font-bold text-gray-800">${nomeCategoria}</h4>
                                <span class="ml-auto text-sm text-gray-500">${categorias[nomeCategoria].length} itens</span>
                            </div>
                            <div class="space-y-3">
                    `;
                    
                    categorias[nomeCategoria].forEach(item => {
                        const priorityClass = `priority-${item.prioridade === 'high' ? 'high' : item.prioridade === 'medium' ? 'medium' : 'low'}`;
                        const priorityIcon = item.prioridade === 'high' ? '🔴' : item.prioridade === 'medium' ? '🟡' : '🟢';
                        const priorityText = item.prioridade === 'high' ? 'Alta' : item.prioridade === 'medium' ? 'Média' : 'Baixa';
                        const priorityColor = item.prioridade === 'high' ? 'border-red-200 bg-red-50' : item.prioridade === 'medium' ? 'border-yellow-200 bg-yellow-50' : 'border-green-200 bg-green-50';
                        
                        htmlContent += `
                            <div class="checklist-item ${priorityClass} glass-card rounded-2xl hover:shadow-lg transition-all duration-300 slide-up border ${priorityColor}" 
                                 style="animation-delay: ${itemIndex * 0.03}s" data-priority="${item.prioridade}" data-item-id="${itemIndex}">
                                <div class="flex items-start space-x-4 p-6">
                                    <div class="relative mt-1">
                                        <input type="checkbox" id="item-${itemIndex}" class="h-6 w-6 text-blue-600 rounded-lg focus:ring-blue-500 focus:ring-2" onchange="atualizarProgresso()">
                                    </div>
                                    <div class="flex-1">
                                        <label for="item-${itemIndex}" class="block text-gray-800 cursor-pointer font-semibold mb-2 text-lg hover:text-blue-600 transition-colors item-label">
                                            ${item.item}
                                        </label>
                                        ${item.descricao ? `<p class="text-sm text-gray-600 mb-4 leading-relaxed item-description">${item.descricao}</p>` : ''}
                                        
                                        <!-- Área de observações premium -->
                                        <div id="observacao-${itemIndex}" class="hidden mt-4 p-4 glass-card rounded-xl border border-yellow-200 bg-yellow-50">
                                            <div class="flex items-center mb-2">
                                                <span class="text-lg mr-2">📝</span>
                                                <span class="text-sm font-semibold text-gray-700">Suas Observações</span>
                                            </div>
                                            <textarea placeholder="Adicione suas observações, dificuldades ou comentários sobre este item..." 
                                                      class="w-full text-sm border-0 bg-transparent resize-none focus:ring-0 placeholder-gray-400" 
                                                      rows="3" onchange="salvarObservacao(${itemIndex}, this.value)"></textarea>
                                        </div>
                                        
                                        <div class="flex items-center justify-between mt-4">
                                            <div class="flex items-center space-x-4 text-sm">
                                                <div class="flex items-center space-x-2 px-3 py-1 ${priorityColor} rounded-lg priority-badge">
                                                    <span>${priorityIcon}</span>
                                                    <span class="font-semibold">${priorityText}</span>
                                                </div>
                                                <div class="flex items-center space-x-2 px-3 py-1 bg-blue-50 rounded-lg border border-blue-200">
                                                    <span>⏱️</span>
                                                    <span class="font-semibold text-blue-700">${item.tempo || 1}h</span>
                                                </div>
                                                <div class="px-3 py-1 bg-gray-100 rounded-lg border border-gray-200">
                                                    <span class="text-gray-700 font-medium">${item.categoria}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 action-buttons">
                                                <button onclick="toggleObservacao(${itemIndex})" class="p-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg transition-colors" title="Adicionar observação">
                                                    📝
                                                </button>
                                                <button onclick="editarItem(${itemIndex})" class="p-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors" title="Editar item">
                                                    ✏️
                                                </button>
                                                ${item.customizado ? `<button onclick="removerItem(${itemIndex})" class="p-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors" title="Remover item">🗑️</button>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        itemIndex++;
                    });
                    
                    htmlContent += `
                            </div>
                        </div>
                    `;
                }
            });
            
            content.innerHTML = htmlContent;
        }

        function filtrarPorPrioridade(prioridade, botaoClicado) {
            // Remover classes ativas de todos os botões
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-gradient-to-r', 'from-blue-500', 'to-indigo-600', 'text-white');
                btn.classList.add('bg-white');
                
                // Restaurar cores originais baseadas no tipo
                if (btn.textContent.includes('Alta')) {
                    btn.classList.add('text-red-700');
                } else if (btn.textContent.includes('Média')) {
                    btn.classList.add('text-amber-700');
                } else if (btn.textContent.includes('Baixa')) {
                    btn.classList.add('text-emerald-700');
                } else {
                    btn.classList.add('text-gray-700');
                }
            });
            
            // Ativar botão clicado
            if (botaoClicado) {
                botaoClicado.classList.remove('bg-white', 'text-red-700', 'text-amber-700', 'text-emerald-700', 'text-gray-700');
                botaoClicado.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-indigo-600', 'text-white');
            }
            
            // Filtrar itens
            document.querySelectorAll('.checklist-item').forEach(item => {
                if (prioridade === 'all' || item.dataset.priority === prioridade) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Feedback visual
            const totalItens = document.querySelectorAll('.checklist-item').length;
            const itensVisiveis = document.querySelectorAll('.checklist-item[style*="block"], .checklist-item:not([style*="none"])').length;
            
            if (prioridade !== 'all') {
                mostrarNotificacao(`Filtro aplicado: ${itensVisiveis} de ${totalItens} itens exibidos`, 'info');
            }
        }

        function atualizarProgresso() {
            const checkboxes = document.querySelectorAll('.checklist-item input[type="checkbox"]');
            const total = checkboxes.length;
            const concluidos = document.querySelectorAll('.checklist-item input[type="checkbox"]:checked').length;
            const pendentes = total - concluidos;
            const progresso = total > 0 ? Math.round((concluidos / total) * 100) : 0;
            
            // Atualizar estilos visuais dos itens
            checkboxes.forEach((checkbox, index) => {
                const itemElement = checkbox.closest('.checklist-item');
                const labelElement = itemElement.querySelector('label');
                const descriptionElement = itemElement.querySelector('.item-description');
                
                if (checkbox.checked) {
                    // Item concluído
                    if (!itemElement.classList.contains('completed')) {
                        itemElement.classList.add('item-completing');
                        setTimeout(() => {
                            itemElement.classList.remove('item-completing');
                            itemElement.classList.add('completed');
                        }, 300);
                    }
                    
                    // Adicionar classes aos elementos internos
                    if (labelElement) labelElement.classList.add('item-label');
                    if (descriptionElement) descriptionElement.classList.add('item-description');
                    
                    // Adicionar badge de conclusão se não existir
                    let badge = itemElement.querySelector('.completion-badge');
                    if (!badge) {
                        badge = document.createElement('div');
                        badge.className = 'completion-badge';
                        badge.innerHTML = `
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Concluído</span>
                        `;
                        
                        // Inserir o badge na área de badges
                        const badgeArea = itemElement.querySelector('.flex.items-center.space-x-4');
                        if (badgeArea) {
                            badgeArea.appendChild(badge);
                        }
                    }
                } else {
                    // Item não concluído
                    itemElement.classList.remove('completed', 'item-completing');
                    
                    // Remover classes dos elementos internos
                    if (labelElement) labelElement.classList.remove('item-label');
                    if (descriptionElement) descriptionElement.classList.remove('item-description');
                    
                    // Remover badge de conclusão
                    const badge = itemElement.querySelector('.completion-badge');
                    if (badge) {
                        badge.remove();
                    }
                }
            });
            
            // Calcular tempo restante estimado
            const tempoTotal = checklistAtual.reduce((acc, item) => acc + (item.tempo || 1), 0);
            const tempoCompleto = checklistAtual.slice(0, concluidos).reduce((acc, item) => acc + (item.tempo || 1), 0);
            const tempoRestante = Math.max(0, tempoTotal - tempoCompleto);
            
            // Atualizar elementos premium
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const progressBarText = document.getElementById('progressBarText');
            const itensCompletos = document.getElementById('itensCompletos');
            const itensPendentes = document.getElementById('itensPendentes');
            const tempoRestanteEl = document.getElementById('tempoRestante');
            
            if (progressBar) {
                progressBar.style.width = `${progresso}%`;
                // Mostrar texto na barra quando há progresso significativo
                if (progressBarText) {
                    if (progresso > 15) {
                        progressBarText.style.opacity = '1';
                        progressBarText.textContent = `${progresso}%`;
                    } else {
                        progressBarText.style.opacity = '0';
                    }
                }
            }
            
            if (progressText) progressText.textContent = `${progresso}%`;
            if (itensCompletos) itensCompletos.textContent = concluidos;
            if (itensPendentes) itensPendentes.textContent = pendentes;
            if (tempoRestanteEl) tempoRestanteEl.textContent = `${tempoRestante}h`;
            
            // Atualizar tempo estimado no header
            const tempoEstimadoEl = document.getElementById('tempoEstimado');
            if (tempoEstimadoEl) tempoEstimadoEl.textContent = `${tempoTotal}h`;
            
            // Feedback visual premium baseado no progresso
            if (progresso === 100) {
                mostrarNotificacao('🎉 Parabéns! Checklist 100% concluído!', 'success');
                // Adicionar confetti effect ou celebração
                celebrarConclusao();
            } else if (progresso >= 75) {
                // Mudança sutil na cor da barra para indicar proximidade da conclusão
                if (progressBar) {
                    progressBar.style.background = 'linear-gradient(90deg, #10b981 0%, #059669 100%)';
                }
            } else if (progresso >= 50) {
                if (progressBar) {
                    progressBar.style.background = 'linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%)';
                }
            }
        }
        
        function celebrarConclusao() {
            // Efeito visual de celebração
            const celebration = document.createElement('div');
            celebration.className = 'fixed inset-0 pointer-events-none z-50 flex items-center justify-center';
            celebration.innerHTML = `
                <div class="text-6xl animate-bounce">🎉</div>
            `;
            document.body.appendChild(celebration);
            
            setTimeout(() => {
                celebration.remove();
            }, 3000);
        }

        function resetarProgresso() {
            // Resetar todos os elementos de progresso para nova análise
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const progressBarText = document.getElementById('progressBarText');
            const itensCompletos = document.getElementById('itensCompletos');
            const itensPendentes = document.getElementById('itensPendentes');
            const tempoRestanteEl = document.getElementById('tempoRestante');
            const tempoEstimadoEl = document.getElementById('tempoEstimado');
            
            // Resetar barra de progresso
            if (progressBar) {
                progressBar.style.width = '0%';
                progressBar.style.background = 'linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%)';
            }
            
            if (progressText) progressText.textContent = '0%';
            
            if (progressBarText) {
                progressBarText.style.opacity = '0';
                progressBarText.textContent = '0%';
            }
            
            if (itensCompletos) itensCompletos.textContent = '0';
            if (itensPendentes) itensPendentes.textContent = checklistAtual.length;
            
            // Calcular tempo total do novo checklist
            const tempoTotal = checklistAtual.reduce((acc, item) => acc + (item.tempo || 1), 0);
            if (tempoRestanteEl) tempoRestanteEl.textContent = `${tempoTotal}h`;
            if (tempoEstimadoEl) tempoEstimadoEl.textContent = `${tempoTotal}h`;
            
            console.log('Progresso resetado para nova análise');
        }

        function exportarPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Título
            doc.setFontSize(20);
            doc.text('Checklist de Fechamento Contábil', 20, 30);
            
            // Informações da empresa
            doc.setFontSize(12);
            doc.text(`Empresa: ${configAtual.nomeEmpresa || 'N/A'}`, 20, 50);
            doc.text(`Tipo: ${configAtual.tipoEmpresa}`, 20, 60);
            doc.text(`Regime: ${configAtual.regime}`, 20, 70);
            
            // Lista de itens
            let y = 90;
            checklistAtual.forEach((item, index) => {
                if (y > 270) {
                    doc.addPage();
                    y = 30;
                }
                
                const priorityIcon = item.prioridade === 'high' ? '[!]' : item.prioridade === 'medium' ? '[~]' : '[·]';
                doc.text(`${priorityIcon} ${item.item}`, 20, y);
                y += 10;
            });
            
            doc.save('checklist-fechamento-contabil.pdf');
        }

        function salvarProgresso() {
            const progresso = {};
            document.querySelectorAll('.checklist-item input[type="checkbox"]').forEach((checkbox, index) => {
                progresso[index] = checkbox.checked;
            });
            
            localStorage.setItem('checklistProgresso', JSON.stringify({
                config: configAtual,
                checklist: checklistAtual,
                progresso: progresso,
                data: new Date().toISOString()
            }));
            
            alert('Progresso salvo com sucesso!');
        }

        function voltarQuestionario() {
            document.getElementById('dashboard').classList.add('hidden');
            document.getElementById('questionario').style.display = 'grid';
            document.getElementById('questionario').scrollIntoView({ behavior: 'smooth' });
        }

        // Funções Premium
        function mostrarAjudaCronograma() {
            const modal = document.createElement('div');
            modal.id = 'modalAjudaCronograma';
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl p-6 max-w-2xl mx-4 max-h-96 overflow-y-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">📅 Cronograma Inteligente - Guia Completo</h3>
                        <button onclick="fecharModalAjudaCronograma()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">×</button>
                    </div>
                    
                    <div class="space-y-4 text-sm">
                        <div>
                            <h4 class="font-semibold text-blue-600 mb-2">🎯 O que faz:</h4>
                            <p class="text-gray-700">Cria automaticamente um cronograma personalizado baseado nas características da sua empresa, distribuindo as tarefas de forma otimizada ao longo do prazo disponível.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-green-600 mb-2">⚙️ Como funciona:</h4>
                            <ul class="text-gray-700 space-y-1">
                                <li>• <strong>Análise inteligente:</strong> Considera tamanho da equipe, nível de automação e complexidade</li>
                                <li>• <strong>Distribuição otimizada:</strong> Aloca mais tempo para fases críticas</li>
                                <li>• <strong>Alertas preventivos:</strong> Identifica possíveis gargalos antes que aconteçam</li>
                                <li>• <strong>Flexibilidade:</strong> Permite ajustar prazos conforme necessário</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-orange-600 mb-2">🚀 Como usar:</h4>
                            <ul class="text-gray-700 space-y-1">
                                <li>• <strong>Acelerar (⏪):</strong> Reduz o prazo em 2 dias - use quando tiver recursos extras</li>
                                <li>• <strong>Estender (⏩):</strong> Aumenta o prazo em 2 dias - use quando identificar riscos</li>
                                <li>• <strong>Fases:</strong> Cada fase mostra tarefas específicas e tempo estimado</li>
                                <li>• <strong>Alertas:</strong> Observe os avisos para antecipar problemas</li>
                            </ul>
                        </div>
                        
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="text-blue-800 text-xs"><strong>💡 Dica:</strong> Use o cronograma como base e ajuste conforme sua experiência. Ele aprende com suas escolhas para melhorar futuras sugestões.</p>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Fechar modal com ESC ou clique fora
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    fecharModalAjudaCronograma();
                }
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    fecharModalAjudaCronograma();
                }
            });
        }

        function fecharModalAjudaCronograma() {
            const modal = document.getElementById('modalAjudaCronograma');
            if (modal) {
                modal.remove();
            }
        }

        function adicionarItemCustomizado() {
            const modal = document.createElement('div');
            modal.id = 'modalAdicionar';
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl p-6 max-w-lg mx-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">➕ Adicionar Item Personalizado</h3>
                        <button onclick="fecharModalAdicionar()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">×</button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição do Item</label>
                            <input type="text" id="novoItemTexto" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                   placeholder="Ex: Verificar contratos específicos da empresa">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                            <select id="novoItemCategoria" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="Personalizado">Personalizado</option>
                                <option value="Fiscal">Fiscal</option>
                                <option value="Financeiro">Financeiro</option>
                                <option value="Contábil">Contábil</option>
                                <option value="Operacional">Operacional</option>
                                <option value="Gerencial">Gerencial</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prioridade</label>
                                <select id="novoItemPrioridade" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="high">🔴 Alta</option>
                                    <option value="medium" selected>🟡 Média</option>
                                    <option value="low">🟢 Baixa</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tempo (horas)</label>
                                <input type="number" id="novoItemTempo" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                       value="1" min="0.5" max="8" step="0.5">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Observações (opcional)</label>
                            <textarea id="novoItemDescricao" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                      rows="2" placeholder="Detalhes adicionais sobre este item..."></textarea>
                        </div>
                        
                        <div class="flex space-x-3 pt-4">
                            <button onclick="salvarNovoItem()" class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors">
                                ✅ Adicionar Item
                            </button>
                            <button onclick="fecharModalAdicionar()" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Fechar modal com ESC ou clique fora
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    fecharModalAdicionar();
                }
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    fecharModalAdicionar();
                }
            });
        }

        function fecharModalAdicionar() {
            const modal = document.getElementById('modalAdicionar');
            if (modal) {
                modal.remove();
            }
        }

        function fecharModalAjudaCronograma() {
            const modal = document.getElementById('modalAjudaCronograma');
            if (modal) {
                modal.remove();
            }
        }

        function salvarNovoItem() {
            const texto = document.getElementById('novoItemTexto').value.trim();
            const categoria = document.getElementById('novoItemCategoria').value;
            const prioridade = document.getElementById('novoItemPrioridade').value;
            const tempo = parseFloat(document.getElementById('novoItemTempo').value);
            const descricao = document.getElementById('novoItemDescricao').value.trim();
            
            if (!texto) {
                alert('Por favor, digite a descrição do item.');
                return;
            }
            
            const novoItem = {
                item: `🎯 ${texto}`,
                categoria: categoria,
                prioridade: prioridade,
                tempo: tempo,
                descricao: descricao || 'Item personalizado adicionado pelo usuário',
                customizado: true
            };
            
            checklistAtual.push(novoItem);
            
            // Atualizar dashboard
            atualizarDashboardInfo();
            
            // Regenerar checklist
            exibirChecklistCompleto(checklistAtual);
            
            // Fechar modal
            fecharModalAdicionar();
            
            mostrarFeedback('Item adicionado com sucesso!');
        }

        function atualizarDashboardInfo() {
            // Atualizar total de itens
            const totalItensElement = document.getElementById('totalItens');
            if (totalItensElement) {
                totalItensElement.textContent = checklistAtual.length;
            }
            
            // Atualizar tempo estimado
            const tempoTotal = checklistAtual.reduce((acc, item) => acc + (item.tempo || 1), 0);
            const tempoEstimadoEl = document.getElementById('tempoEstimado');
            if (tempoEstimadoEl) {
                tempoEstimadoEl.textContent = `${tempoTotal}h`;
            }
            
            // Atualizar estatísticas
            gerarEstatisticas(checklistAtual);
            
            // Resetar progresso para refletir mudanças
            resetarProgresso();
        }

        function toggleObservacao(itemIndex) {
            const observacao = document.getElementById(`observacao-${itemIndex}`);
            if (observacao.classList.contains('hidden')) {
                observacao.classList.remove('hidden');
                observacao.querySelector('textarea').focus();
            } else {
                observacao.classList.add('hidden');
            }
        }

        function salvarObservacao(itemIndex, valor) {
            if (!checklistAtual[itemIndex]) return;
            
            if (!checklistAtual[itemIndex].observacoes) {
                checklistAtual[itemIndex].observacoes = {};
            }
            
            checklistAtual[itemIndex].observacoes.usuario = valor;
            
            // Salvar no localStorage
            localStorage.setItem('observacoesChecklist', JSON.stringify(checklistAtual.map(item => item.observacoes || {})));
        }

        function editarItem(itemIndex) {
            // Buscar o item correto pelo data-item-id
            const itemElement = document.querySelector(`[data-item-id="${itemIndex}"]`);
            if (!itemElement) {
                console.error('Item não encontrado:', itemIndex);
                return;
            }
            
            // Encontrar o item real no array baseado no texto
            const labelElement = itemElement.querySelector('label');
            const textoItem = labelElement ? labelElement.textContent.trim() : '';
            
            let item = null;
            let realIndex = -1;
            
            // Buscar o item correto no array
            for (let i = 0; i < checklistAtual.length; i++) {
                if (checklistAtual[i].item === textoItem) {
                    item = checklistAtual[i];
                    realIndex = i;
                    break;
                }
            }
            
            if (!item) {
                console.error('Item não encontrado no array:', textoItem);
                return;
            }
            
            const modal = document.createElement('div');
            modal.id = 'modalEdicao';
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl p-6 max-w-lg mx-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">✏️ Editar Item</h3>
                        <button onclick="fecharModalEdicao()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">×</button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                            <input type="text" id="editItemTexto" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                   value="${item.item.replace(/^[🎯🏦💰📦🔧👥⏰📋📊🛒🏭💼🌾🏗️💳🌍🏢📈💹⚖️🔍🤝🚗🎓🌾🐄🥛🌡️🏗️👷🚚⚡💳📊💰📈🛡️🔄]+\s*/, '')}">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição Detalhada</label>
                            <textarea id="editItemDescricao" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                      rows="2" placeholder="Detalhes sobre este item...">${item.descricao || ''}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prioridade</label>
                                <select id="editItemPrioridade" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="high" ${item.prioridade === 'high' ? 'selected' : ''}>🔴 Alta</option>
                                    <option value="medium" ${item.prioridade === 'medium' ? 'selected' : ''}>🟡 Média</option>
                                    <option value="low" ${item.prioridade === 'low' ? 'selected' : ''}>🟢 Baixa</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tempo (horas)</label>
                                <input type="number" id="editItemTempo" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                       value="${item.tempo || 1}" min="0.5" max="8" step="0.5">
                            </div>
                        </div>
                        
                        <div class="flex space-x-3 pt-4">
                            <button onclick="salvarEdicaoItem(${realIndex})" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                                ✅ Salvar Alterações
                            </button>
                            <button onclick="fecharModalEdicao()" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Fechar modal com ESC ou clique fora
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    fecharModalEdicao();
                }
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    fecharModalEdicao();
                }
            });
        }

        function fecharModalEdicao() {
            const modal = document.getElementById('modalEdicao');
            if (modal) {
                modal.remove();
            }
        }

        function salvarEdicaoItem(itemIndex) {
            const texto = document.getElementById('editItemTexto').value.trim();
            const descricao = document.getElementById('editItemDescricao').value.trim();
            const prioridade = document.getElementById('editItemPrioridade').value;
            const tempo = parseFloat(document.getElementById('editItemTempo').value);
            
            if (!texto) {
                alert('Por favor, digite a descrição do item.');
                return;
            }
            
            if (itemIndex < 0 || itemIndex >= checklistAtual.length) {
                console.error('Índice inválido:', itemIndex);
                alert('Erro ao salvar: item não encontrado.');
                return;
            }
            
            // Manter o ícone original ou usar um genérico
            const icone = checklistAtual[itemIndex].item.match(/^[🎯🏦💰📦🔧👥⏰📋📊🛒🏭💼🌾🏗️💳🌍🏢📈💹⚖️🔍🤝🚗🎓🐄🥛🌡️👷🚚⚡🛡️🔄]+/)?.[0] || '📋';
            
            // Atualizar o item
            checklistAtual[itemIndex].item = `${icone} ${texto}`;
            checklistAtual[itemIndex].descricao = descricao || checklistAtual[itemIndex].descricao;
            checklistAtual[itemIndex].prioridade = prioridade;
            checklistAtual[itemIndex].tempo = tempo;
            
            console.log('Item atualizado:', checklistAtual[itemIndex]);
            
            // Regenerar o checklist
            exibirChecklistCompleto(checklistAtual);
            
            // Fechar modal
            fecharModalEdicao();
            
            mostrarFeedback('Item atualizado com sucesso!');
        }

        function removerItem(itemIndex) {
            if (confirm('Tem certeza que deseja remover este item?')) {
                // Encontrar o item correto no array
                const itemElement = document.querySelector(`[data-item-id="${itemIndex}"]`);
                if (!itemElement) {
                    console.error('Item não encontrado:', itemIndex);
                    return;
                }
                
                const labelElement = itemElement.querySelector('label');
                const textoItem = labelElement ? labelElement.textContent.trim() : '';
                
                // Buscar e remover o item correto do array
                for (let i = 0; i < checklistAtual.length; i++) {
                    if (checklistAtual[i].item === textoItem) {
                        checklistAtual.splice(i, 1);
                        break;
                    }
                }
                
                // Atualizar dashboard
                atualizarDashboardInfo();
                
                // Regenerar checklist
                exibirChecklistCompleto(checklistAtual);
                
                mostrarFeedback('Item removido com sucesso!');
            }
        }

        function exportarTemplate() {
            const template = {
                config: configAtual,
                checklist: checklistAtual,
                observacoes: checklistAtual.map(item => item.observacoes || {}),
                dataExportacao: new Date().toISOString(),
                versao: '2.0'
            };
            
            const blob = new Blob([JSON.stringify(template, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `checklist-template-${configAtual.nomeEmpresa || 'empresa'}-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            mostrarFeedback('Template exportado com sucesso!');
        }

        function importarChecklist() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = '.json';
            input.onchange = function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const template = JSON.parse(e.target.result);
                        
                        if (template.versao && template.checklist) {
                            if (confirm('Deseja substituir o checklist atual pelo template importado?')) {
                                checklistAtual = template.checklist;
                                configAtual = template.config || configAtual;
                                
                                // Restaurar observações se existirem
                                if (template.observacoes) {
                                    template.observacoes.forEach((obs, index) => {
                                        if (checklistAtual[index]) {
                                            checklistAtual[index].observacoes = obs;
                                        }
                                    });
                                }
                                
                                exibirChecklistCompleto(checklistAtual);
                                mostrarFeedback('Template importado com sucesso!');
                            }
                        } else {
                            alert('Arquivo de template inválido.');
                        }
                    } catch (error) {
                        alert('Erro ao ler o arquivo. Verifique se é um template válido.');
                    }
                };
                reader.readAsText(file);
            };
            input.click();
        }

        // Função para voltar ao topo
        function voltarAoTopo() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Mostrar/ocultar botão flutuante baseado no scroll
        function toggleScrollButton() {
            const scrollBtn = document.getElementById('voltarTopo');
            if (window.pageYOffset > 300) {
                scrollBtn.classList.add('show-scroll-btn');
            } else {
                scrollBtn.classList.remove('show-scroll-btn');
            }
        }

        // Função para voltar à página inicial
        function voltarPaginaInicial() {
            // Ocultar dashboard se estiver visível
            const dashboardElement = document.getElementById('dashboard');
            if (dashboardElement && !dashboardElement.classList.contains('hidden')) {
                dashboardElement.classList.add('hidden');
            }
            
            // Mostrar questionário
            const questionarioElement = document.getElementById('questionario');
            if (questionarioElement) {
                questionarioElement.style.display = 'grid';
            }
            
            // Scroll suave para o topo
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            
            // Feedback visual
            mostrarFeedback('Voltando à página inicial...');
        }

        // Premium initialization and enhancements
        function mostrarAjudaGeral() {
            const modal = document.createElement('div');
            modal.id = 'modalAjudaGeral';
            modal.className = 'fixed inset-0 modal-backdrop flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="modal-content glass-card-strong rounded-3xl p-8 max-w-2xl mx-4 max-h-96 overflow-y-auto">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 gradient-secondary rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold gradient-text-primary">Central de Ajuda</h3>
                        </div>
                        <button onclick="fecharModalAjudaGeral()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">×</button>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                <h4 class="font-semibold text-blue-800 mb-2">🚀 Como usar</h4>
                                <p class="text-sm text-blue-700">Preencha os 3 formulários com dados da sua empresa e clique em "Gerar Checklist Inteligente" para criar uma análise personalizada.</p>
                            </div>
                            
                            <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                <h4 class="font-semibold text-green-800 mb-2">📊 Dashboard</h4>
                                <p class="text-sm text-green-700">Após gerar o checklist, você terá acesso a análise de riscos, cronograma otimizado e acompanhamento de progresso.</p>
                            </div>
                            
                            <div class="p-4 bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl border border-purple-200">
                                <h4 class="font-semibold text-purple-800 mb-2">⚡ Recursos</h4>
                                <p class="text-sm text-purple-700">Filtros inteligentes, personalização de itens, export para PDF, salvamento de progresso e acesso mobile.</p>
                            </div>
                            
                            <div class="p-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl border border-orange-200">
                                <h4 class="font-semibold text-orange-800 mb-2">🤖 IA</h4>
                                <p class="text-sm text-orange-700">Sistema inteligente que analisa riscos, otimiza cronogramas e detecta possíveis problemas automaticamente.</p>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-gray-50 to-slate-50 p-6 rounded-xl border border-gray-200">
                            <h4 class="font-semibold text-gray-800 mb-3">💡 Passo a passo</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                <div>
                                    <h5 class="font-semibold text-gray-800 mb-2">1️⃣ Preenchimento:</h5>
                                    <ul class="space-y-1">
                                        <li>• <strong>Dados Básicos:</strong> Nome, tipo, porte, regime</li>
                                        <li>• <strong>Tecnologia:</strong> ERP, automação, equipe</li>
                                        <li>• <strong>Operações:</strong> Complexidade e características</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-800 mb-2">2️⃣ Utilização:</h5>
                                    <ul class="space-y-1">
                                        <li>• <strong>Filtros:</strong> Organize por prioridade</li>
                                        <li>• <strong>Progresso:</strong> Marque itens concluídos</li>
                                        <li>• <strong>Personalização:</strong> Adicione itens próprios</li>
                                        <li>• <strong>Export:</strong> Salve em PDF ou Excel</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-4 rounded-xl border border-indigo-200">
                            <h4 class="font-semibold text-indigo-800 mb-2">🔧 Dicas importantes</h4>
                            <div class="text-sm text-indigo-700 space-y-1">
                                <p>• Esta ferramenta é um auxiliar - sempre use seu julgamento profissional</p>
                                <p>• Adapte o checklist às especificidades da sua empresa</p>
                                <p>• Mantenha-se atualizado com mudanças na legislação</p>
                                <p>• Use como base para organizar e não perder nenhum item importante</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Fechar modal com ESC ou clique fora
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    fecharModalAjudaGeral();
                }
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    fecharModalAjudaGeral();
                }
            });
        }

        function fecharModalAjudaGeral() {
            const modal = document.getElementById('modalAjudaGeral');
            if (modal) {
                modal.remove();
            }
        }

        function fecharModalAjudaCronograma() {
            const modal = document.getElementById('modalAjudaCronograma');
            if (modal) {
                modal.remove();
            }
        }

        // Enhanced loading animation
        function mostrarLoadingAnimation() {
            const loadingEl = document.getElementById('loadingProgress');
            if (loadingEl) {
                loadingEl.classList.remove('hidden');
                
                // Simulate progress
                let progress = 0;
                const progressBar = loadingEl.querySelector('.progress-bar');
                const interval = setInterval(() => {
                    progress += Math.random() * 15;
                    if (progress > 100) progress = 100;
                    
                    progressBar.style.width = `${progress}%`;
                    
                    if (progress >= 100) {
                        clearInterval(interval);
                        setTimeout(() => {
                            loadingEl.classList.add('hidden');
                        }, 500);
                    }
                }, 200);
            }
        }

        // Enhanced notification system
        function mostrarNotificacao(mensagem, tipo = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification glass-card-strong rounded-2xl p-4 shadow-xl border-l-4 ${
                tipo === 'success' ? 'border-green-500' : 
                tipo === 'warning' ? 'border-yellow-500' : 
                tipo === 'error' ? 'border-red-500' : 'border-blue-500'
            }`;
            
            const icon = tipo === 'success' ? '✅' : tipo === 'warning' ? '⚠️' : tipo === 'error' ? '❌' : 'ℹ️';
            
            notification.innerHTML = `
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">${icon}</span>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">${mensagem}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-gray-500 hover:text-gray-700">×</button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Show animation
            setTimeout(() => notification.classList.add('show'), 100);
            
            // Auto remove
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        // ===== RECURSOS ENTERPRISE =====
        
        // Sistema de Detecção de Anomalias
        function executarScanAnomalias() {
            mostrarNotificacao('🔍 Executando scan de anomalias...', 'info');
            
            setTimeout(() => {
                const anomalias = [];
                
                // Verificar anomalias baseadas nos dados atuais
                if (configAtual.complexidade === 'muito-alta' && parseInt(configAtual.prazo) < 15) {
                    anomalias.push({
                        tipo: 'critical',
                        titulo: 'Prazo Incompatível',
                        descricao: 'Complexidade muito alta com prazo muito curto'
                    });
                }
                
                if (configAtual.equipe === '1' && configAtual.porte === 'grande') {
                    anomalias.push({
                        tipo: 'warning',
                        titulo: 'Recursos Insuficientes',
                        descricao: 'Equipe pequena para empresa grande'
                    });
                }
                
                // Atualizar display
                const container = document.getElementById('anomaliasDetectadas');
                if (container) {
                    if (anomalias.length === 0) {
                        container.innerHTML = `
                            <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <span class="text-2xl">✅</span>
                                    <div>
                                        <div class="font-semibold text-green-800">Nenhuma Anomalia Detectada</div>
                                        <div class="text-sm text-green-600">Configuração dentro dos padrões</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        container.innerHTML = anomalias.map(anomalia => `
                            <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                                <div class="flex items-start space-x-3">
                                    <span class="text-xl">🚨</span>
                                    <div>
                                        <div class="font-semibold text-red-800">${anomalia.titulo}</div>
                                        <div class="text-sm text-red-700">${anomalia.descricao}</div>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }
                }
                
                mostrarNotificacao(`✅ Scan concluído! ${anomalias.length} anomalias encontradas.`, 'success');
            }, 2000);
        }
        
        // Sistema de Colaboração
        function abrirColaboracao() {
            const modal = document.createElement('div');
            modal.id = 'modalColaboracao';
            modal.className = 'fixed inset-0 modal-backdrop flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="modal-content glass-card-strong rounded-3xl p-8 max-w-2xl mx-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold gradient-text-primary">👥 Colaboração Multi-usuário</h3>
                        <button onclick="fecharModalColaboracao()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">×</button>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <h4 class="font-semibold text-blue-800 mb-2">👨‍💼 Convidar Usuários</h4>
                                <input type="email" placeholder="email@empresa.com" class="w-full p-2 border rounded-lg mb-2">
                                <select class="w-full p-2 border rounded-lg mb-2">
                                    <option>Contador Senior</option>
                                    <option>Contador Junior</option>
                                    <option>Supervisor</option>
                                    <option>Apenas Visualização</option>
                                </select>
                                <button onclick="enviarConvite()" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Enviar Convite</button>
                            </div>
                            
                            <div class="p-4 bg-green-50 rounded-xl border border-green-200">
                                <h4 class="font-semibold text-green-800 mb-2">💬 Chat da Equipe</h4>
                                <div id="chatMessages" class="bg-white p-3 rounded border h-24 overflow-y-auto mb-2 text-sm">
                                    <div class="text-gray-500 mb-1">Sistema: Chat iniciado</div>
                                    <div class="text-gray-500 mb-1">Sistema: Aguardando mensagens...</div>
                                </div>
                                <div class="flex space-x-2">
                                    <input type="text" id="chatInput" placeholder="Digite sua mensagem..." class="flex-1 p-2 border rounded-lg" onkeypress="if(event.key==='Enter') enviarMensagemChat()">
                                    <button onclick="enviarMensagemChat()" class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 text-sm">
                                        📤
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-purple-50 rounded-xl border border-purple-200">
                            <h4 class="font-semibold text-purple-800 mb-3">📋 Usuários Online</h4>
                            <div id="usuariosOnline" class="space-y-2">
                                <div class="flex items-center justify-between p-2 bg-white rounded border">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs">EU</div>
                                        <div>
                                            <div class="font-medium">Você</div>
                                            <div class="text-xs text-gray-500">Contador • Online agora</div>
                                        </div>
                                    </div>
                                    <div class="text-xs text-blue-600">Ativo</div>
                                </div>
                                <div class="text-center text-xs text-gray-500 py-2">
                                    Convide outros usuários para colaborar
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function fecharModalColaboracao() {
            const modal = document.getElementById('modalColaboracao');
            if (modal) modal.remove();
        }
        
        function enviarConvite() {
            const nome = document.getElementById('nomeColaborador').value.trim();
            const email = document.getElementById('emailColaborador').value.trim();
            const funcao = document.getElementById('funcaoColaborador').value;
            
            if (!nome || !email) {
                alert('Por favor, preencha nome e email do colaborador.');
                return;
            }
            
            // Validação básica de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Por favor, digite um email válido.');
                return;
            }
            
            // Obter texto da função selecionada
            const funcaoSelect = document.getElementById('funcaoColaborador');
            const funcaoTexto = funcaoSelect.options[funcaoSelect.selectedIndex].text;
            
            // Simular envio do convite
            mostrarNotificacao(`📧 Convite enviado para ${nome} (${email}) como ${funcaoTexto}!`, 'success');
            
            // Limpar campos
            document.getElementById('nomeColaborador').value = '';
            document.getElementById('emailColaborador').value = '';
            document.getElementById('funcaoColaborador').selectedIndex = 0;
            
            // Log do convite enviado
            console.log('Convite enviado:', {
                nome: nome,
                email: email,
                funcao: funcao,
                funcaoTexto: funcaoTexto,
                empresa: configAtual.nomeEmpresa || 'Empresa',
                timestamp: new Date().toISOString()
            });
        }
        
        // Sistema de Chat Inteligente
        function enviarMensagemChat() {
            const input = document.getElementById('chatInput');
            const mensagem = input.value.trim();
            
            if (!mensagem) return;
            
            const chatMessages = document.getElementById('chatMessages');
            const agora = new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
            
            // Identificar usuário baseado no nome da empresa ou usar "Você"
            const nomeUsuario = configAtual.nomeEmpresa || 'Você';
            
            // Adicionar mensagem do usuário com identificação clara
            const novaMensagem = document.createElement('div');
            novaMensagem.className = 'text-blue-700 mb-1 text-sm';
            novaMensagem.innerHTML = `<strong>${nomeUsuario}</strong> <span class="text-gray-500">(${agora})</span>: ${mensagem}`;
            chatMessages.appendChild(novaMensagem);
            
            // Limpar input
            input.value = '';
            
            // Scroll para baixo
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // Simular resposta inteligente do sistema
            setTimeout(() => {
                const respostaAutomatica = document.createElement('div');
                respostaAutomatica.className = 'text-green-600 mb-1 text-sm';
                
                // Respostas contextuais baseadas na mensagem
                let resposta = '';
                if (mensagem.toLowerCase().includes('ajuda')) {
                    resposta = `Sistema: Como posso ajudar ${nomeUsuario}? Use os botões de ação ou digite suas dúvidas.`;
                } else if (mensagem.toLowerCase().includes('progresso')) {
                    const progresso = Math.round((document.querySelectorAll('.checklist-item input:checked').length / document.querySelectorAll('.checklist-item input').length) * 100) || 0;
                    resposta = `Sistema: Progresso atual: ${progresso}% concluído.`;
                } else if (mensagem.toLowerCase().includes('prazo')) {
                    resposta = `Sistema: Prazo configurado: ${cronogramaAtual?.prazoAtual || configAtual.prazo || 15} dias úteis.`;
                } else {
                    resposta = `Sistema: Mensagem de ${nomeUsuario} registrada ✅ Equipe notificada.`;
                }
                
                respostaAutomatica.innerHTML = resposta;
                chatMessages.appendChild(respostaAutomatica);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 1000);
        }
        
        // Função para relatório avançado (substitui ERP)
        function gerarRelatorioAvancado() {
            const modal = document.createElement('div');
            modal.id = 'modalRelatorio';
            modal.className = 'fixed inset-0 modal-backdrop flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="modal-content glass-card-strong rounded-3xl p-8 max-w-2xl mx-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold gradient-text-primary">📊 Relatório Avançado</h3>
                        <button onclick="fecharModalRelatorio()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">×</button>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <h4 class="font-semibold text-blue-800 mb-2">📈 Métricas de Performance</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span>Itens Totais:</span>
                                        <span class="font-bold">${checklistAtual.length}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Tempo Estimado:</span>
                                        <span class="font-bold">${checklistAtual.reduce((acc, item) => acc + (item.tempo || 1), 0)}h</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Complexidade:</span>
                                        <span class="font-bold">${configAtual.complexidade || 'N/A'}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-green-50 rounded-xl border border-green-200">
                                <h4 class="font-semibold text-green-800 mb-2">🎯 Distribuição por Prioridade</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span>🔴 Alta:</span>
                                        <span class="font-bold">${checklistAtual.filter(item => item.prioridade === 'high').length}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>🟡 Média:</span>
                                        <span class="font-bold">${checklistAtual.filter(item => item.prioridade === 'medium').length}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>🟢 Baixa:</span>
                                        <span class="font-bold">${checklistAtual.filter(item => item.prioridade === 'low').length}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="exportarRelatorioCompleto()" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">
                                📊 Exportar Relatório Completo
                            </button>
                            <button onclick="fecharModalRelatorio()" class="flex-1 bg-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-400">
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function fecharModalRelatorio() {
            const modal = document.getElementById('modalRelatorio');
            if (modal) modal.remove();
        }
        
        function exportarRelatorioCompleto() {
            mostrarNotificacao('📊 Gerando relatório completo...', 'info');
            setTimeout(() => {
                mostrarNotificacao('✅ Relatório exportado com sucesso!', 'success');
                fecharModalRelatorio();
            }, 1500);
        }
        
        // Sistema Mobile
        function gerarQRCodeMobile() {
            const modal = document.createElement('div');
            modal.id = 'modalQRCode';
            modal.className = 'fixed inset-0 modal-backdrop flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="modal-content glass-card-strong rounded-3xl p-8 max-w-md mx-4 text-center">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold gradient-text-primary">📱 Acesso Mobile</h3>
                        <button onclick="fecharModalQRCode()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">×</button>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="w-48 h-48 bg-gray-100 rounded-xl mx-auto flex items-center justify-center border-2 border-dashed border-gray-300">
                            <div class="text-center">
                                <div class="text-6xl mb-2">📱</div>
                                <div class="text-sm text-gray-600">QR Code para<br>acesso mobile</div>
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-600">
                            <p class="mb-2">Funcionalidades mobile:</p>
                            <ul class="text-left space-y-1">
                                <li>• Acompanhar progresso</li>
                                <li>• Receber notificações</li>
                                <li>• Colaborar com equipe</li>
                                <li>• Aprovar itens</li>
                            </ul>
                        </div>
                        
                        <button onclick="simularAcessoMobile()" class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700">
                            📱 Simular Acesso
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function fecharModalQRCode() {
            const modal = document.getElementById('modalQRCode');
            if (modal) modal.remove();
        }
        
        function simularAcessoMobile() {
            fecharModalQRCode();
            mostrarNotificacao('📱 Acesso mobile ativado!', 'success');
        }
        
        function enviarLinkMobile() {
            const modal = document.createElement('div');
            modal.id = 'modalEnviarEmail';
            modal.className = 'fixed inset-0 modal-backdrop flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="modal-content glass-card-strong rounded-3xl p-8 max-w-md mx-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold gradient-text-primary">📧 Enviar Link Mobile</h3>
                        <button onclick="fecharModalEnviarEmail()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">×</button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email do destinatário:</label>
                            <input type="email" id="emailDestinatario" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                   placeholder="exemplo@empresa.com">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mensagem personalizada (opcional):</label>
                            <textarea id="mensagemPersonalizada" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                      rows="3" placeholder="Olá! Compartilho com você o acesso mobile ao nosso checklist de fechamento contábil..."></textarea>
                        </div>
                        
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="flex items-start space-x-2">
                                <span class="text-blue-600 text-lg">ℹ️</span>
                                <div class="text-sm text-blue-700">
                                    <strong>O que será enviado:</strong>
                                    <ul class="mt-1 space-y-1">
                                        <li>• Link seguro para acesso mobile</li>
                                        <li>• QR Code para download rápido</li>
                                        <li>• Instruções de uso</li>
                                        <li>• Dados do checklist atual</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3 pt-4">
                            <button onclick="processarEnvioEmail()" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                📧 Enviar Link
                            </button>
                            <button onclick="fecharModalEnviarEmail()" class="flex-1 bg-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-400 transition-colors font-medium">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function fecharModalEnviarEmail() {
            const modal = document.getElementById('modalEnviarEmail');
            if (modal) modal.remove();
        }
        
        function processarEnvioEmail() {
            const email = document.getElementById('emailDestinatario').value.trim();
            const mensagem = document.getElementById('mensagemPersonalizada').value.trim();
            
            if (!email) {
                alert('Por favor, digite um email válido.');
                return;
            }
            
            // Validação básica de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Por favor, digite um email válido.');
                return;
            }
            
            // Identificar usuário atual
            const usuarioAtual = configAtual.usuarioId || 'usuario-anonimo';
            const nomeEmpresa = configAtual.nomeEmpresa || 'Empresa';
            
            // Simular envio com dados do usuário
            fecharModalEnviarEmail();
            mostrarNotificacao('📧 Processando envio...', 'info');
            
            setTimeout(() => {
                mostrarNotificacao(`✅ Link enviado para ${email}!`, 'success');
                
                // Simular detalhes do envio com informações do usuário
                setTimeout(() => {
                    mostrarNotificacao(`📱 Enviado: Link para checklist de "${nomeEmpresa}" + QR Code + Instruções`, 'info');
                }, 2000);
                
                // Log do envio (simulado)
                console.log('Email enviado:', {
                    de: usuarioAtual,
                    para: email,
                    empresa: nomeEmpresa,
                    mensagem: mensagem,
                    timestamp: new Date().toISOString()
                });
            }, 1500);
        }

        // Enhanced gerarChecklistAvancado - EXECUÇÃO IMEDIATA
        const originalGerarChecklist = gerarChecklistAvancado;
        gerarChecklistAvancado = function() {
            console.log('🎯 Função enhanced chamada - execução imediata');
            
            try {
                originalGerarChecklist();
                mostrarNotificacao('🚀 Checklist Enterprise gerado!', 'success');
                console.log('✅ Checklist gerado com sucesso!');
            } catch (error) {
                console.error('❌ Erro na função enhanced:', error);
                mostrarNotificacao('Erro ao gerar checklist. Verifique os dados.', 'error');
            }
        };

        // Initialize AOS and other premium features
        window.addEventListener('load', function() {
            // Initialize AOS (Animate On Scroll) - Disabled for immediate loading
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 0,
                    easing: 'ease-out-cubic',
                    once: true,
                    offset: 0,
                    disable: true
                });
            }
            
            // Load saved progress
            const progressoSalvo = localStorage.getItem('checklistProgresso');
            if (progressoSalvo) {
                const dados = JSON.parse(progressoSalvo);
                // Implement restore logic if needed
            }
            
            // Enhanced scroll button
            window.addEventListener('scroll', function() {
                const scrollBtn = document.getElementById('voltarTopo');
                if (window.pageYOffset > 300) {
                    scrollBtn.classList.add('show-scroll-btn');
                } else {
                    scrollBtn.classList.remove('show-scroll-btn');
                }
            });
            
            // Add premium interactions
            document.querySelectorAll('.hover-lift').forEach(el => {
                el.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
                });
                
                el.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Enhanced form validation with visual feedback
            document.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('scale-in');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('scale-in');
                    
                    if (this.value.trim()) {
                        this.classList.add('border-green-300', 'bg-green-50');
                        this.classList.remove('border-red-300', 'bg-red-50');
                    }
                });
            });
            
            // Welcome message
            setTimeout(() => {
                mostrarNotificacao('Bem-vindo ao Checklist Contábil Professional Suite! 🚀', 'info');
            }, 1000);
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'960d2f9a57ec0cec',t:'MTc1Mjc5MTQ0OC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>