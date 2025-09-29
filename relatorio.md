# Relatório de Testes - Desafio QA

Este documento é um modelo para entrega do resultado:

---

## Dados do Candidato
- **Nome:**  
- **Email:**  
- **NUmero Contato:**  

---

## Plano de Testes

### Objetivo
Descrever o objetivo do teste

### Escopo
Funcionalidades testadas  

### Estratégia de Testes
Explique como os testes foram conduzidos (Tipos e Ferramentas)

### Casos de Teste


### Casos de Teste - API de Cadastro de Usuário

| ID | Cenário                    | Entrada                      | Resultado Esperado             | Resultado Obtido                                        | Status     |
|:--:|:--------------------------:|:----------------------------:|:----------------------- ------:|:-------------------------------------------------------:|:----------:|
| 01 | Cadastro com dados válidos | `email` e `senha` válidos    | Sucesso no cadastro (201)      | `{"success": true}`                                     | **Passou** |
| 02 | E-mail já existente        | `email` repetido             | Erro de e-mail duplicado (409) | `{"success": false, "message": "Email already exists"}` | **Passou** |
| 03 | E-mail em maiúsculas       | `email` em CAIXA ALTA        | Sucesso no cadastro (201)      | `{"success": true}`                                     | **Passou** |
| 04 | **BUG** - E-mail vazio     | `email` como `""`            | Erro de campo obrigatório (400)| Permitiu o cadastro (201)                               | **Falhou** |
| 05 | **BUG** - Senha vazia      | `password` como `""`         | Erro de campo obrigatório (400)| Permitiu o cadastro (201)                               | **Falhou** |
| 06 | **BUG** - E-mail sem "@"   | `email` sem o "@"            | Erro de formato inválido (400) | Permitiu o cadastro (201)                               | **Falhou** |
| 07 | **BUG** - XSS na senha     | `password` com `<script>`    | Erro de entrada inválida (400) | Permitiu o cadastro (201)                               | **Falhou** |

### Casos de Teste - API de Login

| ID | Cenário                           | Entrada                               |    Resultado Esperado                | Resultado Obtido                                         | Status     |
|:--:|:---------------------------------:|:-------------------------------------:|:----------------------------- ------:|:--------------------------------------------------------:|:----------:|
| 01 | Login com credenciais válidas     | `email` e `senha` corretos            | Sucesso no login (200)               | `{"success": true, "message": "Login successful"}`       | **Passou** |
| 02 | Login com senha incorreta         | `email` correto, `senha` errada       | Erro de senha incorreta (401)        | `{"success": false, "message": "Password is incorrect"}` | **Passou** |
| 03 | Login com e-mail não cadastrado   | `email` inexistente                   | Erro de usuário não encontrado (404) | `{"success": false, "message": "User not found"}`        | **Passou** |
| 04 | Login com campo de senha vazio    | `email` correto, `senha` vazia        | Erro de senha incorreta (401)        | `{"success": false, "message": "Password is incorrect"}` | **Passou** |
| 05 | E-mail (Case Insensitive)         | `email` em CAIXA ALTA                 | Sucesso no login (200)               | `{"success": true, "message": "Login successful"}`       | **Passou** |
| 06 | Senha (Case Sensitive)            | `senha` em CAIXA ALTA                 | Erro de senha incorreta (401)        | `{"success": false, "message": "Password is incorrect"}` | **Passou** |
| 07 | Segurança - Tentativa de XSS      | `senha` com `<script>`                | Erro de senha incorreta (401)        | `{"success": false, "message": "Password is incorrect"}` | **Passou** |
| 08 | Segurança - Brute Force (Melhoria)| Múltiplas tentativas com senha errada | Bloqueio de conta ou `rate limit`    | A API continua respondendo normalmente (401)             | **Passou** |

### Casos de Teste - API de Calculadora de Juros

#### Juros Simples

| ID | Cenário                              | Entrada             |   Resultado Esperado              | Resultado Obtido                      | Status     |
|:--:|:------------------------------------:|:-------------------:|:---------------------------------:|:-------------------------------------:|:----------:|
| 01 | Cálculo com taxa inteira             | `rate` como `1`     | Cálculo correto (Juros: 100)      | `{"interest": 100}`                   | **Passou** |
| 02 | Cálculo com taxa decimal (ponto)     | `rate` como `1.5`   | Cálculo correto (Juros: 150)      | `{"interest": 150}`                   | **Passou** |
| 03 | **BUG** - Taxa decimal (vírgula)     | `rate` como `"1,5"` | Calcular juros de 150 ou erro 400 | Calculou juros de 100 (ignorou `,5`)  | **Falhou** |
| 04 | **BUG** - Taxa decimal < 1 (vírgula) | `rate` como `"0,5"` | Calcular juros de 50 ou erro 400  | Calculou juros de 0 (ignorou o valor) | **Falhou** |

---
#### Juros Compostos

| ID | Cenário                                       | Entrada                  | Resultado Esperado             | Resultado Obtido                  | Status     |
|:--:|:--------------------------------------------:|:-------------------------:|:------------------------------:|:---------------------------------:|:----------:|
| 01 | Cálculo com taxa zero                        | `rate` como `0`           | Juros devem ser 0              | `{"interest": 0}`                 | **Passou** |
| 02 | Cálculo com tempo zero                       | `time` como `0`           | Juros devem ser 0              | `{"interest": 0}`                 | **Passou** |
| 03 | **BUG** - Lógica de cálculo (valores padrão) | `P:2000, r:1, t:10, n:10` | Cálculo correto (Juros: ~16.72)| `{"interest": 210.23}` (Incorreto)| **Falhou** |
| 04 | **BUG** - Lógica de cálculo (frequência)     | `P:1000, r:12, t:2, n:12` | Cálculo correto (Juros: 20.10) | `{"interest": 269.73}` (Incorreto)| **Falhou** |
| 05 | **BUG** - Principal negativo                 | `principal` como `-1000`  | Erro de entrada inválida (400) | Calculou com valor negativo (200) | **Falhou** |
| 06 | **BUG** - Frequência zero                    | `frequency` como `0`      | Erro por divisão por zero (400)| `500 Internal Server Error`       | **Falhou** |
| 07 | **BUG** - Tempo > 12 meses                   | `time` como `13`          | Calcular para 13 meses ou erro | Alterou o input `time` para `6.5` | **Falhou** |

---
#### Simulação de Parcelamento

| ID | Cenário                        | Entrada            | Resultado Esperado               | Resultado Obtido                 | Status     |
|:--:|:-------------------------------:|:------------------:|:-------------------------------:|:--------------------------------:|:----------:|
| 01 | Cálculo de parcelamento padrão  | `P:1200, r:1, i:6` | Cálculo correto e saldo final 0 | Sucesso no cálculo (200)         | **Passou** |
| 02 | **BUG** - Cálculo com 1 parcela | `P:2000, r:1, i:1` | Saldo final deve ser 0          | Saldo final negativo (-0.03)     | **Falhou** |
| 03 | **BUG** - Taxa decimal (vírgula)| `rate` como `"1,5"`| Erro de formato inválido (400)  | Erro 400 com mensagem incorreta  | **Falhou** |
*\*P = Principal, r = rate, i = installments*

## Falhas Encontradas

Liste os bugs encontrados, com descrição clara:  


---

## Sugestões de Melhoria

Liste melhorias que poderiam ser aplicadas na aplicação:  

---

## Desenvolvimento / Extras

Se o candidato criou **testes automatizados**, scripts ou qualquer ferramenta extra, pode documentar aqui:  
- Ferramenta utilizada:  
- Repositório ou anexo:  
