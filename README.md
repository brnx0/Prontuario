# 🏥 Prontuário Eletrônico - Saúde Municipal (Santo Amaro/BA) 

![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D)
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

## 📌 Objetivo do Projeto

Este projeto foi desenvolvido com a nobre missão de propor e **levar um melhor, mais moderno e ágil atendimento para a população da rede municipal de saúde na cidade de Santo Amaro, na Bahia**.

Através da digitalização dos registros, históricos de pacientes (Prontuário Eletrônico), triagem, requisições de atendimento e receituários, o sistema visa otimizar o tempo dos profissionais da saúde (Médicos, Enfermeiros e Outras Especialidades) e fornecer um acompanhamento contínuo e digno para cada cidadão santamarense, consolidando as informações clínicas em uma interface unificada, rápida e segura.

## 🚀 Arquitetura e Tecnologias

O sistema adota uma arquitetura moderna baseada em conceitos de **SPA (Single Page Application)**, utilizando toda a robustez e segurança do ecossistema PHP juntamente a reatividade do Javascript moderno.

- **Back-end:** Laravel 11 (PHP 8.2+) utilizando _Service/Repository Pattern_ para regras de negócio estruturadas.
- **Front-end:** Vue.js 3 (Composition API).
- **Integração Bridge:** Inertia.js (permitindo requisições client-side dinâmicas utilizando o próprio roteamento monolítico do Laravel sem depender de APIs RESTfull espalhadas).
- **Design & UI:** Tailwind CSS (com tema Clínico moderno, Design System e suporte automático e manual a Dark/Light Mode) com interações e alertas nativos via SweetAlert2.
- **Autenticação:** Laravel Fortify (Session Auth).

## 🛠️ Como Instalar e Rodar Localmente

Para rodar este projeto e contribuir com o código na sua máquina local de desenvolvimento, siga as instruções abaixo rigorosamente. A sua máquina precisará possuir previamente o **PHP 8.2+**, **Composer** e do **Node.js (LTS)** instalados.

### 1. Clonar o Repositório

```bash
git clone https://github.com/brnx0/Prontuario
cd Prontuario
```

### 2. Instalar Dependências do PHP (Back-end)

```bash
composer install
```

### 3. Instalar Dependências do Node (Front-end)

```bash
npm install
```

### 4. Configurar Variáveis de Ambiente

```bash
cp .env.example .env
```

Abra o arquivo `.env` gerado na pasta raiz e configure os dados de conexão com o Servidor de Banco de Dados local que você usar (MySQL, PostgreSQL, MariaDB ou SQLite). Exemplo MySQL padrão:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_seu_banco_aqui
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### 5. Configurar Proteção e Banco

Gere a chave de criptografia de Sessions e Cookies do projeto:

```bash
php artisan key:generate
```

Crie as Tabelas do SQL diretamente através das Migrations do Laravel:

```bash
php artisan migrate
```

Para criar ou popular usuários médicos de teste padrão automaticamente, rode `php artisan migrate --seed`

### 6. Iniciar os Servidores Locais Plenos

Para criar a harmonia perfeita entre o Vue.js e o PHP, você precisará de **dois processos paralelos (Terminais)** abertos simultaneamente na raiz do projeto:

**Terminal 1 (Inicia o Core e PHP do Laravel na Porta 8000):**

```bash
php artisan serve
```

**Terminal 2 (Inicia o Motor Vite para empacotar o JavaScript e Tailwind em tempo real HMR):**

```bash
npm run dev
```

Pronto! Agora sua API de compilação estará verde e pronta. Basta acessar `http://localhost:8000` no seu navegador predileto!

---

_Projeto tecnológico desenvolvido e idealizado com honra e dedicação exclusiva para a saúde pública de Santo Amaro/BA._
