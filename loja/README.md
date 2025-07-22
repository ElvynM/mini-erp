# 🛒 Projeto Loja - Laravel Sail + Docker Desktop

---

## ✅ Pré-requisitos

- 🐳 **Docker Desktop** instalado e rodando (Windows, Mac ou Linux)  
  [https://www.docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop)

- 💻 **WSL 2** habilitado (para usuários Windows)  

---

## 🚀 Passo a passo para rodar o projeto

### 1. 📥 Clonar o repositório (ou baixar o código)

```bash
git clone git@github.com:ElvynM/mini-erp.git
cd mini-erp

2. 🐳 Subir os containers com Laravel Sail
./vendor/bin/sail up -d

3. 🗄️ Rodar as migrations para preparar o banco de dados

./vendor/bin/sail artisan make:migration create_produtos_table --create=produtos


4 Comando para criar model

./vendor/bin/sail artisan make:model Produto


4. 🌐 Acessar a aplicação no navegador

http://localhost:80

5 criar a chave : Execute o comando para gerar a chave:

 ./vendor/bin/sail artisan key:generate


 