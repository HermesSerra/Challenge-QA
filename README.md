# Challenge-QA

Este projeto foi desenvolvido para **avaliar as habilidades de QA** (Quality Assurance) em testes de API.  
O objetivo é validar a capacidade do candidato em identificar falhas, propor cenários de teste e garantir a qualidade das funcionalidades apresentadas.  

---

## Produto

O sistema consiste em um conjunto de APIs simples.

### 1) API de Cadastro de Usuário
- Permite cadastrar um **e-mail** e uma **senha**.  

---

### 2) API de Login
- Permite autenticação utilizando **e-mail** e **senha**.  

---

### 3) API de Calculadora de Juros
- Endpoints para:  
  - Cálculo de **juros simples**.  
  - Cálculo de **juros compostos**.  
  - Simulação de **parcelamento**.  

---

## Infraestrutura

O projeto já vem configurado com containers para facilitar a execução em ambiente local:  

- **Docker Compose** → orquestra os serviços do projeto.  
- **Dockerfile (MySQL)** → container para o banco de dados.  
- **Dockerfile (Backend PHP 8 + Apache)** → container para a aplicação backend.  
- **Entrypoint** → script de inicialização do projeto.  

Para executar o pojeto basta execuatar `docker-compose up -d`

---

## Aplicação

- Desenvolvida em **PHP 8** utilizando o framework **Slim** para a criação das APIs.  
- Banco de dados gerenciado via **Doctrine Migrations**.  
- Script auxiliar para inicialização automática do ambiente.  

---

## Objetivo do Desafio

O candidato deve:  
1. Explorar os endpoints disponíveis.  
2. Identificar **falhas funcionais e de negócio**.  
3. Criar casos de teste que validem cenários positivos e negativos.  
4. Documentar os defeitos encontrados e sugerir melhorias.  
