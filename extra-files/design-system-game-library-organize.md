# **🎨 Design System — Game Library Organize**

Documento de especificação visual, arquitetura de UI/UX e tokens de design do sistema **Game Library Organize** (Organizador de Bibliotecas Digitais de Jogos). Otimizado para interpretação direta e prática por agentes de IA e desenvolvedores Laravel \+ Vue.js.

## **📚 1. Visão Geral e Contexto de Execução**

* **Nome do Projeto**: Game Library Organize  
* **Descrição**: Sistema unificado para organização, gestão, acompanhamento de progresso e avaliação de jogos adquiridos em múltiplas plataformas e bibliotecas.  
* **Autor**: Giovani Cerejo Brasil  
* **Versão**: 1.0.0  
* **Data de Criação**: 2026-08-22  
* **Público-Alvo**: Gamers e entusiastas de jogos que possuem contas espalhadas em diversas plataformas (Steam, Epic, GOG, PlayStation, Xbox, Switch, etc.) e necessitam de uma centralização rica e visual.  
* **Filosofia de UI**: Estilo retrô-moderno aconchegante, limpo, de alta legibilidade, com suporte nativo a temas Escuro (Dark) e Claro (Light), além de permitir que o usuário personalize a cor principal e secundária do seu dashboard.

## **🎨 2. Tokens de Design (Design Tokens)**

### **2.1 Paleta de Cores Padrão**

O sistema suporta personalização de cores pelo usuário em tempo de execução através de **CSS Variables**. As cores base padrão são:

| Nome do Token | Código HEX | Uso Primário |
| :---- | :---- | :---- |
| brandPrimary | \#182075 | Cor primária base do sistema, cabeçalhos do dashboard, destaques principais (Personalizável pelo usuário) |
| brandSecondary | \#751919 | Cor de destaque/ação secundaria, botões CTA de ação crítica, badges de destaque (Personalizável pelo usuário) |
| surfaceBackgroundDark | \#0F0F0F | Fundo principal da aplicação em Dark Mode (Padrão inicial da Landing Page e Dashboard) |
| surfaceBackgroundLight | \#FAFAFA | Fundo principal da aplicação quando o Light Mode estiver ativado |
| surfaceCardDark | \#1A1A24 | Fundo de cards, modais e containers em Dark Mode |
| surfaceCardLight | \#FFFFFF | Fundo de cards, modais e containers em Light Mode |
| borderDark | \#2A2A3B | Divisores, bordas de inputs e cards em Dark Mode |
| borderLight | \#E0E0E0 | Divisores e bordas em Light Mode |
| textPrimaryDark | \#FAFAFA | Texto principal em Dark Mode |
| textPrimaryLight | \#0F0F0F | Texto principal em Light Mode |
| textMuted | \#94A3B8 | Textos secundários, legendas e rótulos de menor relevância |

#### **Cores Semânticas de Status de Jogos & Classificação**

| Nome do Token | Código HEX | Uso em Jogos |
| :---- | :---- | :---- |
| statusFinished | \#2E7D32 | Badge e indicador de jogo **Finalizado** |
| statusPlaying | \#0288D1 | Badge de jogo **Em Andamento / Jogando** |
| statusDropped | \#C62828 | Badge de jogo **Desistido / Abandonado** |
| statusBacklog | \#F57F17 | Badge de jogo **Não Finalizado / Na Fila (Backlog)** |
| ratingStar | \#FFB800 | Cor de destaque das estrelas de avaliação (0 a 5\) |

### **2.2 Tipografia**

O projeto utiliza três famílias tipográficas com responsabilidades bem definidas:

1. **Ubuntu**: Títulos, headers, badges retrô-modernos e destaques visuais.  
2. **Open Sans**: Subtítulos, navegação de menus, rótulos de filtros e botões.  
3. **Roboto**: Corpo de texto, formulários, reviews longas e descrições detalhadas.

| Token Tipográfico | Família | Peso (FontWeight) | Tamanho (px) | Height | Uso de Destino |
| :---- | :---- | :---- | :---- | :---- | :---- |
| displayLarge | Ubuntu | Bold (700) | 36px | 1.2 | Título da Landing Page e nomes de jogos em destaque |
| titleLarge | Ubuntu | Bold (700) | 24px | 1.3 | Títulos de seções, páginas de detalhes e relatórios |
| titleMedium | Open Sans | SemiBold (600) | 18px | 1.4 | Cabeçalhos de cards de jogos e títulos de modais |
| titleSmall | Open Sans | Medium (500) | 16px | 1.4 | Subtítulos de seções e rótulos de filtros |
| bodyLarge | Roboto | Regular (400) | 15px | 1.5 | Texto do resumo do jogo e reviews dos usuários |
| bodyMedium | Roboto | Regular (400) | 13px | 1.5 | Rótulos de campos de busca, tabelas e informações técnicas |
| caption | Roboto | Regular (400) | 11px | 1.4 | Legendas, badges de plataformas/bibliotecas |
| buttonText | Open Sans | SemiBold (600) | 14px | 1.2 | Botões de ação, login e filtros |

### **2.3 Grid, Espaçamento e Proporções de Mídia**

Unidade Base: 8px

* space4 \= 4px (Gaps mínimos de ícones e tags)  
* space8 \= 8px (Padding de badges, filtros compactos)  
* space12 \= 12px (Gaps em formulários e cards)  
* space16 \= 16px (Padding padrão de cards e containers)  
* space24 \= 24px (Padding de seções e modais)  
* space32 \= 32px (Margens externas de layouts desktop)

#### **Proporções Visuais (Aspect Ratios)**

* **Capa do Jogo (Portrait Cover)**: Proporção 2:3 (Ex: 300x450px).  
* **Imagem de Background (Hero Header)**: Proporção 16:9 ou 21:9 com gradiente escuro em overlay para legibilidade.  
* **Vídeo do Trailer Incorporado**: Container expansível com proporção 16:9.

### **2.4 Elevações e Arredondamento (Elevations & Border Radius)**

* **Border Radius**:  
  * radiusSmall \= 4px (Chips de plataformas, badges de classificação)  
  * radiusMedium \= 8px (Botões, inputs, cards de jogos no grid)  
  * radiusLarge \= 16px (Modais, containers de relatórios, hero da página de detalhes)  
* **Sombras (Shadows)**:  
  * glowRetro: 0 0 15px rgba(24, 32, 117, 0.4) (Efeito suave em hover de cards de jogos)  
  * elevationLow: 0 2px 8px rgba(0, 0, 0, 0.3)  
  * elevationHigh: 0 10px 30px rgba(0, 0, 0, 0.6)

## **🛠️ 3. Código de Configuração de Temas & CSS Variables**

Para garantir a troca dinâmica de cores e Light/Dark Mode no Vue.js \+ CSS, utilize as seguintes variáveis raiz:

/\* resources/css/theme.css \*/  
:root {  
  \--brand-primary: \#182075;  
  \--brand-secondary: \#751919;  
  \--bg-main: \#0f0f0f;  
  \--bg-card: \#1a1a24;  
  \--border-color: \#2a2a3b;  
  \--text-main: \#fafafa;  
  \--text-muted: \#94a3b8;  
    
  \--status-finished: \#2e7d32;  
  \--status-playing: \#0288d1;  
  \--status-dropped: \#c62828;  
  \--status-backlog: \#f57f17;  
  \--star-color: \#ffb800;  
}

\[data-theme="light"\] {  
  \--bg-main: \#fafafa;  
  \--bg-card: \#ffffff;  
  \--border-color: \#e0e0e0;  
  \--text-main: \#0f0f0f;  
  \--text-muted: \#64748b;  
}

## **🧱 4. Catálogo de Componentes de Interface (UI Components)**

### **4.1 Identidade Visual e Logos**

* **Logo Principal (images/logo/logo.png)**:  
  * Aplicada na Landing Page (Header em destaque), Tela de Login e Tela de Cadastro.  
* **Logo Ícone (images/logo/icon.png)**:  
  * Exibida na barra lateral/topo do Dashboard do usuário logado.  
* **Favicon (images/logo/favicon.ico)**:  
  * Ícone da aba do navegador.

### **4.2 Página Inicial (Landing Page), Login e Cadastro**

* **Página Inicial**:  
  * Fundo animado em tom escuro, unindo o moderno com o retrô (ex: malha de vetores sutis ou partículas lentas estilo anos 80/90).  
  * Apresentação clara do sistema, seção de Dúvidas Frequentes (FAQ no estilo accordion) e botão de chamada para Login/Cadastro.  
* **Páginas de Login e Cadastro**:  
  * Formulários centralizados sobre o mesmo fundo animado da Landing Page.  
  * Inputs limpos com bordas reagentes à cor brandPrimary.

### **4.3 Dashboard — Tela Inicial e Listagem de Jogos**

Layout no formato de grid responsivo otimizado para exibição de capas:

\+-------------------------------------------------------------------------------+  
| \[Icon Logo\] Game Library Organize   \[Busca por nome...       \] \[Tema\] \[Perfil\]|  
\+-------------------------------------------------------------------------------+  
| Filtros: \[Plataforma v\] \[Biblioteca v\] \[Status v\] \[Ano v\] \[Gênero v\] \[+ Mais\] |  
\+-------------------------------------------------------------------------------+  
| \+--------------+  \+--------------+  \+--------------+  \+--------------+    |  
| | Capa (2:3)   |  | Capa (2:3)   |  | Capa (2:3)   |  | Capa (2:3)   |    |  
| |              |  |              |  |              |  |              |    |  
| | Finalizado   |  | Em Andamento |  | Desistido    |  | Backlog      |    |  
| | Zelda: BotW  |  | Cyberpunk    |  | Elden Ring   |  | Witcher 3    |    |  
| | ★ 5.0 | Switch| | ★ 4.5 | PC   |  | ★ 3.0 | PS5  |  | ★ \--  | Steam|    |  
| \+--------------+  \+--------------+  \+--------------+  \+--------------+    |  
\+-------------------------------------------------------------------------------+

#### **Card de Jogo no Grid:**

* **Capa Portrait**: Aspect-ratio 2:3 com animação de escala leve no hover (transform: scale(1.03)).  
* **Badge de Status**: Posicionado no canto superior do card (statusFinished, statusPlaying, statusDropped, statusBacklog).  
* **Informações Rápidas**: Título do jogo, média da avaliação com estrelas (ratingStar) e ícones/chips das plataformas em que o usuário possui o jogo.

### **4.4 Tela de Informação Detalhada do Jogo**

Uma tela imersiva projetada para ser elegante e aconchegante:

* **Hero Banner**: Imagem de background do jogo ocupando o topo com gradiente suave transicionando para a cor \--bg-main.  
* **Bloco Principal**:  
  * **Capa Portrait** em alta resolução destacada à esquerda.  
  * **Informações Estruturadas**: Nome, Resumo/Sinopse, Ano de Lançamento, Desenvolvedora, Publicadora, Franquia (se houver), Classificação Indicativa e Links diretos de compra.  
  * **Plataformas e Bibliotecas**: Badges ilustradas indicando onde o usuário possui o jogo (Ex: Steam, Switch, Epic).  
  * **Trailer Incorporado**: Player de vídeo do YouTube estilizado e responsivo.  
* **Seção de Progresso e Avaliação Pessoal**:  
  * Seletor de Status (Finalizado, Não Finalizado, Desistiu).  
  * Contador de Tempo de Jogo em Horas.  
  * Avaliação por estrelas de 0 a 5\.  
  * Campo para Avaliação Escrita (Review Pessoal).

### **4.5 Filtros Avançados (Elasticsearch Powered)**

Painel superior expansível contendo os seguintes campos:

* **Busca Textual**: Nome do jogo, Desenvolvedora ou Publicadora.  
* **Seletores Multi-select**:  
  * Plataformas (Switch, Xbox, PlayStation, PC ou personalizadas).  
  * Bibliotecas (Steam, Epic Games, GOG, Ubisoft Connect, EA App, Rockstar, Xbox PC, Amazon Luna ou personalizadas).  
  * Status (Finalizado, Não Finalizado, Desistência).  
  * Ano de Lançamento e Gênero.

### **4.6 Painel de Relatórios e Gráficos**

Interface visual analítica alimentada por gráficos modernos:

* **Métricas Principais**: Total de Jogos, Total de Horas Jogadas, Jogos Finalizados %, Taxa de Desistência.  
* **Gráficos Visuais**:  
  * Gráfico de Pizza/Doughnut: Distribuição por Plataformas e Bibliotecas.  
  * Gráfico de Barras: Jogos por Gênero, Desenvolvedora e Publicadora.  
  * Linha do Tempo: Lançamentos e Jogos Concluídos por Ano.

### **4.7 Tela de Configurações do Usuário**

* **Personalização de Cores**: Color pickers para alterar a cor \--brand-primary e \--brand-secondary.  
* **Alternador de Tema**: Switch rápido entre Light Mode e Dark Mode.  
* **Gestão de Plataformas e Bibliotecas Personalizadas**: Formulário para adicionar e editar plataformas/bibliotecas além das iniciais padrão.

## **🎲 5. Dados Iniciais do Sistema**

### **5.1 Plataformas Iniciais**

1. Nintendo Switch  
2. Xbox (Microsoft)  
3. PlayStation (Sony)  
4. PC

### **5.2 Bibliotecas Iniciais**

1. Steam  
2. Epic Games  
3. GOG  
4. Ubisoft Connect  
5. EA App  
6. Rockstar Launcher  
7. Xbox PC  
8. Amazon Games / Amazon Luna

## **🤖 6. Diretrizes Diretas para Agentes de IA**

1. **Uso das Variáveis de Cores**: NUNCA utilize cores HEX hardcoded em componentes Vue. Utilize sempre as variáveis CSS configuradas (ex: var(--brand-primary)).  
2. **Capas de Jogos**: Assegure-se de que todas as capas sigam estritamente o aspecto visual 2:3 para evitar distorções nas imagens.  
3. **Persistência de Tema**: O tema selecionado (Light/Dark) e as cores personalizadas do usuário devem ser salvas no perfil e no localStorage para carregamento imediato sem flickering.  
4. **Resoluções Otimizadas**: Garanta navegação fluida em telas Desktop (1920x1080 e 1366x768) e adaptação responsiva para telas menores.