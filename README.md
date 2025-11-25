## 🌎 Idioma/Language

<details open>
<summary>🇧🇷 Português</summary>

# 📚 Gerenciador de Bibliotecas (BookLover)
Sistema web desenvolvido para auxiliar na administração de bibliotecas, permitindo controle de livros, clientes, funcionários e empréstimos, além de relatórios e análises visualmente organizadas.

---

## ✨ Funcionalidades
**Cadastro e gerenciamento de:**
- Livros
- Clientes
- Funcionários
- Empréstimos

✔ **Edição e exclusão de registros**  
✔ **Upload de capa de imagens para os livros**  
✔ **Consulta automática de endereço via CEP (API ViaCEP)**  
✔ **Dashboard com gráficos interativos (Chart.js)**  
✔ **Geração de relatórios em PDF e CSV**  
✔ **Mensagens modais para avisos e confirmações**  
✔ **Conexão segura usando `.env`**

---

## 🛠 Tecnologias utilizadas
- **PHP**
- **MySQL**
- **XAMPP**
- **HTML, CSS e JavaScript**
- **Chart.js**
- **ViaCEP API**
- **FPDF (para relatórios PDF)**

---

## 🗄 Estrutura do Banco de Dados

O sistema possui tabelas para:

- `livros`
- `clientes`
- `funcionarios`
- `emprestimos`

---

## 📊 Dashboard
O painel principal possui gráficos dinâmicos exibindo estatísticas como:

- Empréstimos por gênero
- Quantidade de livros cadastrados
- Indicadores gerais da biblioteca

---

📂 Estrutura do Projeto
``` 📂gerenciaBibliotecas
├── .env  
├── .gitignore    
├── README.md  
│
├── 📂css  
│   ├── dashboard.css  
│   ├── form.css  
│   ├── modal.css  
│   └── style.css  
│
├── 📂html  
│   └── index.html  
│
├── 📂img  
│   ├── chuttersnap-Zf64Osndqvc-unsplash.jpg  
│   ├── livro1.jpg  
│   ├── livro2.jpg  
│   ├── livro3.jpg  
│   └── pngegg.png  
│
├── 📂js  
│   ├── cep.js  
│   ├── dashboard.js  
│   ├── mascaras.js  
│   └── theme.js  
│
├── 📂libs  
│   ├── fpdf.php  
│   └── font  
│       ├── courier.php  
│       ├── courierb.php  
│       ├── courierbi.php  
│       ├── courieri.php  
│       ├── helvetica.php  
│       ├── helveticab.php  
│       ├── helveticabi.php  
│       ├── helveticai.php  
│       ├── symbol.php  
│       ├── times.php  
│       ├── timesb.php  
│       ├── timesbi.php  
│       ├── timesi.php  
│       └── zapfdingbats.php  
│
├── 📂php  
│   ├── cadastro.php  
│   ├── clientes.php  
│   ├── conexao.php  
│   ├── dashboard.php  
│   ├── dashboard_data.php  
│   ├── emprestimos.php  
│   ├── env.php  
│   ├── gerar_csv.php  
│   ├── gerar_relatorio.php  
│   ├── livros.php  
│   ├── login.php  
│   └── logout.php  
│
├── 📂sql  
│   └── banco_clube.sql  
│
└── 📂uploads 
 ```

---

## 👩‍💻 Sobre o projeto
O BookLover foi desenvolvido como projeto acadêmico para estudo e prática de desenvolvimento web utilizando PHP, MySQL, integração com APIs, gráficos e geração de relatórios.

 ---

## 📌 Melhorias Futuras

- Sistema de login com níveis de permissão
- Melhor interface mobile
- Paginação de registros
</details>

---

<details>
<summary>🇺🇸 English</summary>

# 📚 Library Manager (BookLover)
Web system designed to assist in the management of libraries, allowing control of books, customers, employees, and loans, in addition to visually organized reports and analyses.

---

## ✨ Features
**Registration and management of:**
- Books  
- Customers  
- Employees  
- Loans  

✔ **Edit and delete records**  
✔ **Book cover image upload**  
✔ **Automatic address lookup via ZIP code (ViaCEP API)**  
✔ **Dashboard with interactive charts (Chart.js)**  
✔ **PDF and CSV report generation**  
✔ **Modal messages for alerts and confirmations**  
✔ **Secure connection using `.env`**

---

## 🛠 Technologies Used
- **PHP**
- **MySQL**
- **XAMPP**
- **HTML, CSS and JavaScript**
- **Chart.js**
- **ViaCEP API**
- **FPDF (for PDF reports)**

---

## 🗄 Database Structure

The system contains the following tables:

- `livros` (books)  
- `clientes` (customers)  
- `funcionarios` (employees)  
- `emprestimos` (loans)

---

## 📊 Dashboard
The main panel contains dynamic charts displaying statistics such as:

- Loans by genre
- Number of registered books
- General library indicators

---

📂 Project Structure
``` 📂gerenciaBibliotecas
├── .env  
├── .gitignore    
├── README.md  
│
├── 📂css  
│   ├── dashboard.css  
│   ├── form.css  
│   ├── modal.css  
│   └── style.css  
│
├── 📂html  
│   └── index.html  
│
├── 📂img  
│   ├── chuttersnap-Zf64Osndqvc-unsplash.jpg  
│   ├── livro1.jpg  
│   ├── livro2.jpg  
│   ├── livro3.jpg  
│   └── pngegg.png  
│
├── 📂js  
│   ├── cep.js  
│   ├── dashboard.js  
│   ├── mascaras.js  
│   └── theme.js  
│
├── 📂libs  
│   ├── fpdf.php  
│   └── font  
│       ├── courier.php  
│       ├── courierb.php  
│       ├── courierbi.php  
│       ├── courieri.php  
│       ├── helvetica.php  
│       ├── helveticab.php  
│       ├── helveticabi.php  
│       ├── helveticai.php  
│       ├── symbol.php  
│       ├── times.php  
│       ├── timesb.php  
│       ├── timesbi.php  
│       ├── timesi.php  
│       └── zapfdingbats.php  
│
├── 📂php  
│   ├── cadastro.php  
│   ├── clientes.php  
│   ├── conexao.php  
│   ├── dashboard.php  
│   ├── dashboard_data.php  
│   ├── emprestimos.php  
│   ├── env.php  
│   ├── gerar_csv.php  
│   ├── gerar_relatorio.php  
│   ├── livros.php  
│   ├── login.php  
│   └── logout.php  
│
├── 📂sql  
│   └── banco_clube.sql  
│
└── 📂uploads 
 ```

---

## 👩‍💻 About the Project
BookLover was developed as an academic project for studying and practicing web development using PHP, MySQL, API integration, chart visualization and report generation.

---

## 📌 Future Improvements

- Login system with permission levels  
- Improved mobile interface  
- Record pagination
</details>

