<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2014 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This file is part of psx. psx is free software: you can
 * redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * psx is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with psx. If not, see <http://www.gnu.org/licenses/>.
 */

namespace PSX;

/**
 * Template
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class Template implements TemplateInterface
{
	protected $dir;
	protected $file;
	protected $data = array();

	public function setDir($dir)
	{
		$this->dir = $dir;
	}

	public function getDir()
	{
		return $this->dir;
	}

	public function set($file)
	{
		$this->file = $file;
	}

	public function get()
	{
		return $this->file;
	}

	public function hasFile()
	{
		return !empty($this->file);
	}

	public function fileExists()
	{
		return $this->file instanceof \Closure || is_file($this->getFile());
	}

	public function getFile()
	{
		return $this->dir != null ? $this->dir . '/' . $this->file : $this->file;
	}

	public function assign($key, $value)
	{
		$this->data[$key] = $value;
	}

	public function transform()
	{
		if($this->file instanceof \Closure)
		{
			$html = call_user_func($this->file, $this->data);
		}
		else
		{
			// populate the data vars in the scope of the template
			extract($this->data, EXTR_SKIP);

			// parse template
			ob_start();

			require_once($this->getFile());

			$html = ob_get_clean();

			if($html === false)
			{
				throw new Exception('Ouput buffering is not active');
			}
		}

		return $html;
	}
}
