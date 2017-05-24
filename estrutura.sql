DROP TABLE IF EXISTS `arquivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `arquivos` (
  `tipo` varchar(20) NOT NULL DEFAULT '',
  `genero` varchar(40) NOT NULL DEFAULT '',
  `arquivo` varchar(100) NOT NULL DEFAULT '',
  `tempo` int(11) NOT NULL DEFAULT '0',
  `data_inicio` date NOT NULL DEFAULT '2004-01-01',
  `data_fim` date NOT NULL DEFAULT '2038-12-31',
  `dia_semana` set('1','2','3','4','5','6','7') NOT NULL DEFAULT '1,2,3,4,5,6,7',
  `hora_inicio` int(11) NOT NULL DEFAULT '0',
  `hora_fim` int(11) NOT NULL DEFAULT '86399',
  `rede` varchar(30) NOT NULL DEFAULT '',
  `loja` varchar(30) NOT NULL DEFAULT '',
  `tocou` char(3) NOT NULL DEFAULT 'Nao',
  PRIMARY KEY (`arquivo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `esquemas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `esquemas` (
  `esquema` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`esquema`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `generos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `generos` (
  `tipo` varchar(20) NOT NULL DEFAULT '',
  `genero` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`genero`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grades` (
  `esquema` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sequencia` int(11) NOT NULL DEFAULT '0',
  `tipo` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `genero` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`esquema`,`sequencia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `playlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `playlists` (
  `tipo` varchar(20) NOT NULL DEFAULT '',
  `genero` varchar(40) NOT NULL DEFAULT '',
  `arquivo` varchar(100) NOT NULL DEFAULT '',
  `hora_inicio` int(11) NOT NULL DEFAULT '0',
  `hora_fim` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `programacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programacoes` (
  `rede` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `loja` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `esquema` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `data_inicio` date NOT NULL DEFAULT '2004-01-01',
  `data_fim` date NOT NULL DEFAULT '2010-12-31',
  `dia_semana` set('1','2','3','4','5','6','7') COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `hora_inicio` int(11) NOT NULL DEFAULT '0',
  `hora_fim` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rede`,`loja`,`data_inicio`,`data_fim`,`dia_semana`,`hora_inicio`,`hora_fim`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `tipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos` (
  `tipo` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`tipo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
LOCK TABLES `tipos` WRITE;
/*!40000 ALTER TABLE `tipos` DISABLE KEYS */;
INSERT INTO `tipos` VALUES ('comercial'),('musical');
/*!40000 ALTER TABLE `tipos` ENABLE KEYS */;
UNLOCK TABLES;
