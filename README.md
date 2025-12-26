# THOMAS videomaker - Portfólio de Aftermovies

Site em PHP com banco de dados SQL local para portfólio de aftermovies de festas (raves).

## 🎨 Características

- Design moderno e imersivo com fundo preto fosco e detalhes em verde vibrante
- Totalmente responsivo
- Painel administrativo completo
- Upload de vídeos e thumbnails
- Sistema de categorias: Aftermovie Evento | Aftermovie DJ | Vídeo Drop
- Suporte para vídeos do YouTube, Vimeo ou arquivos locais
- Sistema de visualizações
- Vídeos em destaque

## 📋 Requisitos

- PHP 7.4 ou superior
- MySQL/MariaDB
- Servidor web (Apache/Nginx) ou PHP built-in server
- Extensões PHP: PDO, PDO_MySQL

## 🚀 Instalação

### 1. Configurar o Banco de Dados

1. Importe o arquivo `database.sql` no seu MySQL:
   ```bash
   mysql -u root -p < database.sql
   ```
   
   Ou execute via phpMyAdmin/Adminer.

2. Configure as credenciais do banco em `config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'thomas_videomaker');
   ```

### 2. Configurar Permissões

Certifique-se de que os diretórios de upload têm permissão de escrita:
```bash
mkdir -p uploads/videos uploads/thumbnails
chmod 755 uploads uploads/videos uploads/thumbnails
```

### 3. Acesso ao Painel Admin

**Credenciais padrão:**
- Usuário: `admin`
- Senha: `admin123`

⚠️ **IMPORTANTE:** Altere a senha após o primeiro acesso!

Para alterar a senha, você pode:
1. Fazer login no painel admin
2. Ou executar este SQL (substitua 'sua_nova_senha' pela senha desejada):
   ```sql
   UPDATE admins SET password = '$2y$10$...' WHERE username = 'admin';
   ```
   Use `password_hash('sua_nova_senha', PASSWORD_DEFAULT)` em PHP para gerar o hash.

### 4. Executar o Servidor

**Opção 1: PHP Built-in Server**
```bash
php -S localhost:8000
```

**Opção 2: Apache/Nginx**
Configure seu servidor web para apontar para a pasta do projeto.

## 📁 Estrutura de Arquivos

```
douglastomas/
├── admin/              # Painel administrativo
│   ├── login.php       # Página de login
│   ├── dashboard.php   # Dashboard principal
│   ├── videos.php      # Lista de vídeos
│   ├── upload.php      # Upload de vídeos
│   ├── edit_video.php  # Editar vídeo
│   ├── delete_video.php # Deletar vídeo
│   └── logout.php      # Logout
├── api/                # APIs
│   ├── get_video.php   # Buscar vídeo
│   └── increment_views.php # Incrementar visualizações
├── assets/
│   ├── css/
│   │   ├── style.css   # Estilos do site
│   │   └── admin.css   # Estilos do admin
│   └── js/
│       └── main.js     # JavaScript principal
├── uploads/            # Arquivos enviados
│   ├── videos/         # Vídeos
│   └── thumbnails/     # Thumbnails
├── config.php          # Configurações
├── database.sql        # Estrutura do banco
├── index.php           # Página principal
└── README.md           # Este arquivo
```

## 🎬 Como Usar

### Adicionar Vídeo

1. Acesse o painel admin: `http://localhost/admin/login.php`
2. Faça login com as credenciais padrão
3. Vá em "Upload" no menu
4. Preencha os dados:
   - **Título** (obrigatório)
   - **Categoria** (obrigatório): Aftermovie Evento | Aftermovie DJ | Vídeo Drop
   - **URL do Vídeo**: Link do YouTube, Vimeo ou caminho do arquivo
   - **Thumbnail**: Imagem de capa (opcional)
   - **Duração**: Ex: 3:45 (opcional)
   - **Destaque**: Marque para aparecer na seção "Em Destaque"

### Tipos de Vídeo Suportados

- **YouTube**: Cole a URL completa do vídeo
- **Vimeo**: Cole a URL completa do vídeo
- **Arquivo Local**: Faça upload do arquivo de vídeo (MP4, WebM, OGG)

## 🎨 Personalização

### Cores

As cores principais estão definidas em `assets/css/style.css`:
```css
:root {
    --black: #0a0a0a;
    --black-matte: #1a1a1a;
    --green: #00ff88;
    --green-dark: #00cc6a;
    --green-light: #33ffaa;
}
```

### Nome do Site

Altere em `config.php`:
```php
define('SITE_NAME', 'THOMAS videomaker');
```

## 🔒 Segurança

- As senhas são armazenadas com `password_hash()`
- Proteção contra SQL Injection usando PDO prepared statements
- Validação de tipos de arquivo no upload
- Proteção de diretórios sensíveis via `.htaccess`

## 📱 Responsividade

O site é totalmente responsivo e funciona em:
- Desktop
- Tablet
- Mobile

## 🐛 Troubleshooting

### Erro de conexão com banco de dados
- Verifique as credenciais em `config.php`
- Certifique-se de que o MySQL está rodando
- Verifique se o banco `thomas_videomaker` foi criado

### Erro ao fazer upload
- Verifique permissões dos diretórios `uploads/`
- Verifique o `upload_max_filesize` no `php.ini`
- Verifique o `post_max_size` no `php.ini`

### Vídeos não aparecem
- Verifique se os vídeos foram cadastrados no painel admin
- Verifique se a URL do vídeo está correta
- Para arquivos locais, verifique se o caminho está correto

## 📝 Licença

Este projeto foi criado para uso pessoal/portfólio.

## 👨‍💻 Desenvolvido por

THOMAS videomaker

---

**Versão:** 1.0.0

