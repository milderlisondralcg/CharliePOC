/*    ==Scripting Parameters==

    Source Database Engine Edition : Microsoft Azure SQL Database Edition
    Source Database Engine Type : Microsoft Azure SQL Database

    Target Database Engine Edition : Microsoft Azure SQL Database Edition
    Target Database Engine Type : Microsoft Azure SQL Database
*/

/****** Object:  Table [dbo].[LeadsDownloads]    Script Date: 5/15/2018 11:38:57 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[LeadsDownloads](
	[DocumentId] [int] NULL,
	[Id] [int] IDENTITY(1,1) NOT NULL,
	[CreatedDatetime] [datetime] NULL,
	[UID] [int] NOT NULL,
	[DocumentTitle] [varchar](max) NULL,
	[UserIpAddress] [varchar](max) NULL,
	[SentStatus] [varchar](10) NOT NULL,
	[AppCode] [varchar](max) NULL,
	[ProductGroup] [varchar](max) NULL
)
GO

ALTER TABLE [dbo].[LeadsDownloads] ADD  CONSTRAINT [DF_SentStatus]  DEFAULT ('No') FOR [SentStatus]
GO

